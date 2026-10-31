<?php

namespace App\Services;

use App\Exceptions\NonRetryableIngestionException;
use App\Exceptions\RetryableIngestionException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeminiEmbeddingService
{
    private const DIMENSIONS = 768;

    /**
     * @param  array<int, string>  $texts
     * @return array<int, string>
     */
    public function embedDocuments(array $texts, array $context = []): array
    {
        if ($texts === []) {
            throw new NonRetryableIngestionException('Cannot embed an empty batch.');
        }

        $attempt = 0;
        $response = null;

        try {
            $response = Http::baseUrl($this->baseUrl())
                ->acceptJson()
                ->connectTimeout((int) config('gemini.connect_timeout', 10))
                ->timeout((int) config('gemini.request_timeout', 30))
                ->retry(
                    3,
                    function (int $retryAttempt, Throwable $exception) use (&$attempt, $texts, $context): int {
                        $attempt = $retryAttempt;
                        $delay = $this->retryDelayMilliseconds($retryAttempt, $exception);

                        Log::warning('Retrying Gemini document embedding request.', [
                            ...$context,
                            'batch_size' => count($texts),
                            'attempt' => $retryAttempt,
                            'delay_ms' => $delay,
                            'status' => $this->exceptionStatus($exception),
                        ]);

                        return $delay;
                    },
                    when: function (Throwable $exception) use ($texts, $context): bool {
                        return $this->isRetryable($exception, $texts, $context);
                    },
                )
                ->withQueryParameters(['key' => (string) config('gemini.api_key')])
                ->post('models/gemini-embedding-001:batchEmbedContents', [
                    'requests' => array_map(
                        static fn (string $text): array => [
                            'model' => 'models/gemini-embedding-001',
                            'content' => [
                                'parts' => [
                                    ['text' => $text],
                                ],
                            ],
                            'taskType' => 'RETRIEVAL_DOCUMENT',
                            'outputDimensionality' => self::DIMENSIONS,
                        ],
                        $texts,
                    ),
                ])
                ->throw();
        } catch (RetryableIngestionException|NonRetryableIngestionException $exception) {
            throw $exception;
        } catch (ConnectionException $exception) {
            throw new RetryableIngestionException(
                'Gemini embedding request failed after retries: '.$exception->getMessage(),
                previous: $exception,
            );
        } catch (RequestException $exception) {
            $status = $exception->response->status();

            throw ($this->isRetryableStatus($status)
                ? new RetryableIngestionException(
                    "Gemini embedding request failed with HTTP {$status}.",
                    previous: $exception,
                )
                : new NonRetryableIngestionException(
                    "Gemini embedding request failed with HTTP {$status}.",
                    previous: $exception,
                ));
        }

        Log::info('Gemini document embedding request succeeded.', [
            ...$context,
            'batch_size' => count($texts),
            'attempt' => $attempt + 1,
        ]);

        return $this->validateAndFormat($response->json('embeddings'), count($texts));
    }

    /**
     * @param  mixed  $embeddingRows
     * @return array<int, string>
     */
    private function validateAndFormat(mixed $embeddingRows, int $expectedCount): array
    {
        if (! is_array($embeddingRows) || count($embeddingRows) !== $expectedCount) {
            throw new NonRetryableIngestionException(
                'Gemini returned an incomplete or malformed embeddings payload.',
            );
        }

        $vectors = [];

        foreach (array_values($embeddingRows) as $index => $embeddingRow) {
            $values = is_array($embeddingRow)
                ? ($embeddingRow['values'] ?? $embeddingRow['embedding']['values'] ?? null)
                : null;

            if (! is_array($values) || count($values) !== self::DIMENSIONS) {
                throw new NonRetryableIngestionException(
                    "Gemini returned an invalid vector at embedding index {$index}.",
                );
            }

            foreach ($values as $value) {
                if ((! is_int($value) && ! is_float($value) && ! (is_string($value) && is_numeric($value)))
                    || ! is_finite((float) $value)) {
                    throw new NonRetryableIngestionException(
                        "Gemini returned a non-finite vector value at embedding index {$index}.",
                    );
                }
            }

            $vectors[$index] = '['.implode(',', array_map(
                static fn (int|float|string $value): string => (string) $value,
                $values,
            )).']';
        }

        return $vectors;
    }

    private function isRetryable(Throwable $exception, array $texts, array $context): bool
    {
        $status = $this->exceptionStatus($exception);
        $retryable = $exception instanceof ConnectionException || $this->isRetryableStatus($status);

        if (! $retryable) {
            Log::error('Gemini document embedding request failed permanently.', [
                ...$context,
                'batch_size' => count($texts),
                'attempt' => 1,
                'status' => $status,
                'error' => $exception->getMessage(),
            ]);
        }

        return $retryable;
    }

    private function retryDelayMilliseconds(int $attempt, Throwable $exception): int
    {
        $retryAfter = $exception instanceof RequestException
            ? $exception->response->header('Retry-After')
            : null;

        if (is_numeric($retryAfter)) {
            $base = max(0, (int) $retryAfter * 1000);
        } elseif (is_string($retryAfter) && ($timestamp = strtotime($retryAfter)) !== false) {
            $base = max(0, ($timestamp - time()) * 1000);
        } else {
            $base = 200 * (2 ** max(0, $attempt - 1));
        }

        return $base + random_int(0, 250);
    }

    private function exceptionStatus(Throwable $exception): ?int
    {
        return $exception instanceof RequestException ? $exception->response->status() : null;
    }

    private function isRetryableStatus(?int $status): bool
    {
        return $status === 408 || $status === 425 || $status === 429 || ($status !== null && $status >= 500);
    }

    private function baseUrl(): string
    {
        return rtrim((string) (config('gemini.base_url') ?: 'https://generativelanguage.googleapis.com/v1beta'), '/');
    }
}
