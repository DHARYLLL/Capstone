<?php

namespace App\Services;

class TextChunker
{
    private const DEFAULT_CHUNK_SIZE = 500;

    private const DEFAULT_OVERLAP = 50;

    /**
     * Normalize extracted PDF text before chunking.
     *
     * This removes obvious page noise such as repeated page numbers, table-of-contents
     * entries, and short header/footer lines that tend to repeat across pages.
     */
    public static function cleanExtractedText(string $text): string
    {
        $normalizedText = self::sanitizeExtractedText($text);
        $normalizedText = str_replace(["\r\n", "\r"], "\n", $normalizedText);
        $lines = preg_split('/\n+/u', $normalizedText) ?: [];
        $lineFrequency = [];

        foreach ($lines as $line) {
            $normalizedLine = self::normalizeLine($line);

            if ($normalizedLine !== '') {
                $lineFrequency[$normalizedLine] = ($lineFrequency[$normalizedLine] ?? 0) + 1;
            }
        }

        $cleanLines = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            $normalizedLine = self::normalizeLine($trimmedLine);

            if ($normalizedLine === '') {
                continue;
            }

            if (self::shouldDropBoilerplateLine($trimmedLine, $normalizedLine, $lineFrequency)) {
                continue;
            }

            $cleanLines[] = $trimmedLine;
        }

        return trim((string) preg_replace('/\s+/u', ' ', implode(' ', $cleanLines)));
    }

    private static function sanitizeExtractedText(string $text): string
    {
        $text = str_replace(["\0", "\xC2\xA0", "\xA0"], ' ', $text);
        $text = strip_tags($text);
        $text = (string) preg_replace('/<[^>]*>/u', '', $text);
        $text = (string) preg_replace('/<>/', '', $text);
        $text = (string) preg_replace('/[\x{FFFD}\x{E000}-\x{F8FF}]/u', ' ', $text);
        $text = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', ' ', $text);

        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * Split text into overlapping chunks without cutting words in half.
     *
     * The defaults are intentionally small to reduce embedding latency and keep each
     * chunk close to the model's retrieval sweet spot.
     *
     * @return array<int, string>
     */
    public static function chunk(string $text, int $chunkSize = self::DEFAULT_CHUNK_SIZE, int $overlap = self::DEFAULT_OVERLAP): array
    {
        $normalizedText = trim((string) preg_replace('/\s+/u', ' ', $text));

        if ($normalizedText === '') {
            return [];
        }

        $chunkSize = max(1, $chunkSize);
        $overlap = max(0, min($overlap, $chunkSize - 1));

        $chunks = [];
        $textLength = mb_strlen($normalizedText);
        $start = 0;

        while ($start < $textLength) {
            $end = min($start + $chunkSize, $textLength);

            if ($end < $textLength) {
                $boundaryEnd = self::findBoundaryEnd($normalizedText, $start, $end);

                if ($boundaryEnd !== null) {
                    $end = $boundaryEnd;
                }
            }

            $chunk = trim(mb_substr($normalizedText, $start, $end - $start));

            if ($chunk !== '') {
                $chunks[] = $chunk;
            }

            if ($end >= $textLength) {
                break;
            }

            $nextStart = max(0, $end - $overlap);
            $alignedStart = self::findBoundaryStart($normalizedText, $start, $nextStart);

            if ($alignedStart !== null) {
                $nextStart = $alignedStart;
            }

            if ($nextStart <= $start) {
                $nextStart = $end;
            }

            while ($nextStart < $textLength && self::isBoundaryChar(mb_substr($normalizedText, $nextStart, 1))) {
                $nextStart++;
            }

            $start = $nextStart;
        }

        return $chunks;
    }

    /**
     * @param  array<string, int>  $lineFrequency
     */
    private static function shouldDropBoilerplateLine(string $line, string $normalizedLine, array $lineFrequency): bool
    {
        if (self::isPageNumberLine($line) || self::isTableOfContentsLine($line)) {
            return true;
        }

        if (($lineFrequency[$normalizedLine] ?? 0) >= 3 && mb_strlen($normalizedLine) <= 120) {
            return true;
        }

        return false;
    }

    private static function normalizeLine(string $line): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', mb_strtolower($line)));
    }

    private static function isPageNumberLine(string $line): bool
    {
        return preg_match('/^(page\s*)?\d+(\s*\/\s*\d+)?$/iu', trim($line)) === 1;
    }

    private static function isTableOfContentsLine(string $line): bool
    {
        $trimmedLine = trim($line);

        if (preg_match('/^(table of contents|contents)$/iu', $trimmedLine) === 1) {
            return true;
        }

        return preg_match('/^.+\.{2,}\s*\d+$/u', $trimmedLine) === 1;
    }

    private static function findBoundaryEnd(string $text, int $start, int $end): ?int
    {
        for ($index = $end - 1; $index > $start; $index--) {
            if (self::isBoundaryChar(mb_substr($text, $index, 1))) {
                return $index + 1;
            }
        }

        return null;
    }

    private static function findBoundaryStart(string $text, int $start, int $desiredStart): ?int
    {
        for ($index = $desiredStart; $index > $start; $index--) {
            if (self::isBoundaryChar(mb_substr($text, $index - 1, 1))) {
                return $index;
            }
        }

        return null;
    }

    private static function isBoundaryChar(string $character): bool
    {
        return preg_match('/[\s\p{P}]/u', $character) === 1;
    }
}
