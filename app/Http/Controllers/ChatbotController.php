<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    // public function chat(BusinessUnit $businessUnit): View
    // {
    //     // Hardcode business unit ID = 1 for testing
    //     $businessUnit = BusinessUnit::findOrFail(1);
    //     return view('chat', [
    //         'businessUnit' => $businessUnit,
    //     ]);
    // }

    // public function ask(Request $request, BusinessUnit $businessUnit): JsonResponse
    // {
    //     // Hardcode business unit ID = 1 for testing
    //     $businessUnit = BusinessUnit::findOrFail(1);
    //     $validated = $request->validate([
    //         'prompt' => ['required', 'string', 'max:5000'],
    //     ]);

    //     // 1. Fetch knowledge base facts
    //     $knowledgeBase = $businessUnit->businessKnowledge()
    //         ->pluck('content')
    //         ->implode("\n\n");

    //     // 2. Define System Instructions
    //     $systemInstructions = "You are a helpful customer service AI for this business.\n" .
    //         "Answer the user's question accurately using ONLY the business facts provided below.\n\n" .
    //         "RULES:\n" .
    //         "1. You ARE allowed to perform basic calculations (adding costs, quantities, discounts).\n" .
    //         "2. If facts do not contain the answer, politely state that you do not have that information.\n" .
    //         "3. Mirror the user's language/dialect and format responses clearly with bullet points where appropriate.\n\n" .
    //         "BUSINESS FACTS:\n" . $knowledgeBase;

    //     try {
    //         $sessionKey = "chat_history_{$businessUnit->id}";
    //         $rawHistory = session($sessionKey, []);

    //         // 3. Clean and format conversation history
    //         $conversationContext = "";
    //         if (!empty($rawHistory)) {
    //             $conversationContext .= "CONVERSATION HISTORY:\n";
    //             // Take up to last 8 messages (4 turns)
    //             $recentTurns = array_slice($rawHistory, -8);
    //             foreach ($recentTurns as $turn) {
    //                 $speaker = $turn['role'] === 'user' ? 'User' : 'Assistant';
    //                 // Strip raw quotes or escaped characters that break JSON encodings
    //                 $cleanText = str_replace(["\r", "\n"], " ", trim($turn['text']));
    //                 $conversationContext .= "{$speaker}: {$cleanText}\n";
    //             }
    //             $conversationContext .= "\n";
    //         }

    //         // 4. Construct complete prompt payload
    //         $fullPrompt = "{$systemInstructions}\n\n{$conversationContext}USER QUESTION:\n{$validated['prompt']}";

    //         // 5. Query Gemini
    //         $response = Gemini::generativeModel('gemini-2.5-flash')
    //             ->generateContent($fullPrompt);

    //         $rawResponse = $response->text();

    //         if (empty(trim($rawResponse))) {
    //             $rawResponse = "I couldn't process that request. Could you please rephrase?";
    //         }

    //         // 6. Save new turn to session
    //         $rawHistory[] = ['role' => 'user', 'text' => $validated['prompt']];
    //         $rawHistory[] = ['role' => 'model', 'text' => $rawResponse];

    //         // Keep last 8 entries in session
    //         session([$sessionKey => array_slice($rawHistory, -8)]);

    //         return response()->json([
    //             'message'  => $validated['prompt'],
    //             'response' => Str::markdown($rawResponse),
    //         ]);

    //     } catch (\Exception $e) {
    //         // Log the exact exception for easy inspection in storage/logs/laravel.log
    //         logger()->error('Gemini Chat Error: ' . $e->getMessage(), [
    //             'exception' => $e
    //         ]);

    //         return response()->json([
    //             'message'  => $validated['prompt'],
    //             'response' => "Sorry, an error occurred while generating a response.",
    //         ], 500);
    //     }
    // }

    public function widget(Request $request): View
{
    // 1. Get the business parameter passed from JS (?business=dariv or ?business=1)
    $businessParam = $request->query('business');

    // 2. Safely find business unit by ID or Name, or fallback to first BU in DB
    $businessUnit = BusinessUnit::query()
        ->when($businessParam, function ($query) use ($businessParam) {
            $query->where(function ($q) use ($businessParam) {
                if (is_numeric($businessParam)) {
                    $q->where('id', (int) $businessParam);
                } else {
                    $q->where('name', $businessParam);
                }
            });
        })
        ->first() ?? BusinessUnit::first(); // Uses first available record instead of 404ing

    if (! $businessUnit) {
        abort(404, 'No business units configured.');
    }

    // 3. Get user_id passed from query string
    $userId = $request->query('user_id', 'guest');

    // 4. Return view
    return view('chat.widget', compact('businessUnit', 'userId'));
}

    public function chat(BusinessUnit $businessUnit): View
    {
        return view('chat', [
            'businessUnit' => $businessUnit,
        ]);
    }


    public function ask(Request $request, BusinessUnit $businessUnit): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:5000'],
            'user_identifier' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'string', 'max:255'],
        ]);

        $promptText = trim($validated['prompt']);
        $userIdentifier = trim((string) ($validated['user_identifier'] ?? $validated['user_id'] ?? 'guest'));

        if ($userIdentifier === '' || $userIdentifier === 'guest') {
            $userIdentifier = 'guest_' . Str::uuid()->toString();
        }

        $session = ChatSession::firstOrCreate(
            [
                'company_id' => $businessUnit->company_id,
                'business_unit_id' => $businessUnit->id,
                'user_identifier' => $userIdentifier,
            ],
            [
                'status' => 'bot_active',
            ]
        );

        $customerMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'customer',
            'message_text' => $promptText,
        ]);

        if ($session->status === 'human_active') {
            return response()->json([
                'status' => 'success',
                'session_id' => $session->id,
                'message_id' => $customerMessage->id,
            ]);
        }

        if ($session->status === 'pending') {
            return response()->json([
                'status' => 'pending',
                'session_id' => $session->id,
                'message_id' => $customerMessage->id,
            ]);
        }

        $normalized = mb_strtolower($promptText);
        if ($this->containsHandoffTrigger($normalized)) {
            $this->markSessionHumanActive($session);

            return response()->json([
                'status' => 'human_active',
                'message' => $promptText,
                'response' => 'Connecting you to a live representative...',
                'session_id' => $session->id,
                'message_id' => $customerMessage->id,
            ]);
        }
        $isGreetingOnly = (bool) preg_match(
            '/^(hi|hello|hey|good morning|good afternoon|good evening|yo|hola)[!,.?\\s]*$/iu',
            $normalized
        );

        if ($isGreetingOnly) {
            $greetingReply = "Hello. Welcome to {$businessUnit->name}. How can I help you today?";

            $botMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'bot',
                'message_text' => $greetingReply,
            ]);

            return response()->json([
                'status' => 'bot_active',
                'message' => $promptText,
                'response' => Str::markdown($greetingReply),
                'session_id' => $session->id,
                'message_id' => $botMessage->id,
                'customer_message_id' => $customerMessage->id,
                'distance' => null,
            ]);
        }

        try {
            $queryEmbedding = $this->embedUserPrompt($promptText);

            $candidateRows = collect();
            $bestDistance = 1.0;

            if (! empty($queryEmbedding)) {
                $candidateRows = $businessUnit->businessKnowledge()
                    ->select(['content', 'embedding'])
                    ->selectRaw('embedding::extensions.vector <=> ?::extensions.vector AS distance', [$queryEmbedding])
                    ->whereNotNull('embedding')
                    ->orderByRaw('embedding::extensions.vector <=> ?::extensions.vector ASC', [$queryEmbedding])
                    ->limit(5)
                    ->get();

                if ($candidateRows->isNotEmpty()) {
                    $bestDistance = (float) ($candidateRows->min('distance') ?? 1.0);
                }
            }

            $knowledgeBase = $candidateRows->isNotEmpty()
                ? $candidateRows->pluck('content')->implode("\n\n")
                : 'No relevant knowledge snippets were found in the business knowledge base.';

            $historyMessages = $session->chatMessages()->latest()->limit(8)->get()->reverse();
            $conversationContext = '';

            foreach ($historyMessages as $historyMessage) {
                $speaker = $historyMessage->sender_type === 'user' ? 'User' : 'Assistant';
                $safeText = str_replace(["\r", "\n"], ' ', trim((string) $historyMessage->message_text));
                $conversationContext .= "{$speaker}: {$safeText}\n";
            }

            $systemInstructions =
                "You are a helpful customer service AI for {$businessUnit->name}.\n" .
                "Use BUSINESS FACTS as your primary source of truth.\n\n" .
                "RULES:\n" .
                "1. If user asks a greeting/small-talk, reply politely.\n" .
                "2. For business-specific questions, answer from BUSINESS FACTS.\n" .
                "3. If facts are missing, clearly say you do not have enough information.\n" .
                "4. Keep responses concise and customer-friendly.\n\n" .
                "5. Mirror the user's language/dialect and format responses clearly with bullet points where appropriate.\n\n" .
                "BUSINESS FACTS:\n{$knowledgeBase}";

            $fullPrompt =
                "{$systemInstructions}\n\n" .
                "CONVERSATION HISTORY:\n{$conversationContext}\n" .
                "USER QUESTION:\n{$promptText}";

            $response = Gemini::generativeModel('gemini-3.6-flash')->generateContent($fullPrompt);
            $rawResponse = trim((string) $response->text());

            if ($rawResponse === '') {
                $rawResponse = "I couldn't find enough information to answer that clearly. Could you rephrase your question?";
            }

            $session->update(['status' => 'bot_active']);

            $botMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'bot',
                'message_text' => $rawResponse,
            ]);

            return response()->json([
                'status' => 'bot_active',
                'message' => $promptText,
                'response' => Str::markdown($rawResponse),
                'session_id' => $session->id,
                'message_id' => $botMessage->id,
                'customer_message_id' => $customerMessage->id,
                'distance' => $candidateRows->isNotEmpty() ? round($bestDistance, 4) : null,
            ]);
        } catch (\Throwable $e) {
            logger()->error('Gemini Chat Error', [
                'business_unit_id' => $businessUnit->id,
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'bot_active',
                'message' => $promptText,
                'response' => 'Sorry, an error occurred while generating a response.',
                'session_id' => $session->id,
                'customer_message_id' => $customerMessage->id,
            ], 500);
        }
    }

    public function getMessages(int $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);

        $messagesQuery = $session->chatMessages()->orderBy('created_at');

        if ($session->handed_off_at) {
            $messagesQuery = $messagesQuery->where('created_at', '>=', $session->handed_off_at);
        }

        return response()->json([
            'status' => $session->status,
            'assigned_user_id' => $session->assigned_user_id ?? null,
            'handed_off_at' => $session->handed_off_at?->toISOString(),
            'messages' => $messagesQuery->get()->map(function ($message) {
                return [
                    'id' => $message->id,
                    'chat_session_id' => $message->chat_session_id,
                    'sender_type' => $message->sender_type,
                    'message_text' => $message->message_text,
                    'created_at' => $message->created_at?->toISOString(),
                ];
            })->values(),
        ]);
    }

    public function getContactInfo(Request $request): JsonResponse
    {
        try {
            $businessParam = trim((string) ($request->query('business') ?? ''));
            $sessionId = $request->query('session_id') ?? $request->query('sessionId');
            $session = $sessionId ? ChatSession::find($sessionId) : null;
            $businessUnit = $session?->business_unit_id
                ? BusinessUnit::find($session->business_unit_id)
                : null;

            if (! $businessUnit && $businessParam !== '') {
                $businessUnit = BusinessUnit::where('name', 'LIKE', "%{$businessParam}%")
                    ->first();
            }

            $businessUnit ??= BusinessUnit::first();

            if (! $businessUnit) {
                return response()->json([
                    'message' => "We couldn't retrieve our direct contact details right now, but please hang tight—an agent will be with you shortly!",
                ]);
            }

            $contactPrompt = 'Provide official contact information including phone, email, and operating hours for DARIV Waterproofing';
            $systemInstructions =
                "You are a contact information extractor for {$businessUnit->name}. " .
                "Extract ONLY the direct contact information (Phone Number, Email Address, Operating Hours) from the provided facts. " .
                "Do NOT include pricing, services, guarantees, or warranty details under any circumstances. " .
                "Format the output cleanly in 3 bullet points.";

            $botReply = $this->generateAiResponse($contactPrompt, $businessUnit, $systemInstructions);

            return response()->json(['response' => $botReply]);
        } catch (\Throwable $e) {
            logger()->error('Contact lookup failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => "We couldn't retrieve our direct contact details right now, but please hang tight—an agent will be with you shortly!",
            ]);
        }
    }

    private function generateAiResponse(string $prompt, BusinessUnit $businessUnit, ?string $systemInstructions = null): string
    {
        $embedding = $this->embedUserPrompt($prompt);
        $knowledgeRows = collect();

        if (! empty($embedding)) {
            $knowledgeRows = $businessUnit->businessKnowledge()
                ->select(['content', 'embedding'])
                ->selectRaw('embedding::extensions.vector <=> ?::extensions.vector AS distance', [$embedding])
                ->whereNotNull('embedding')
                ->orderByRaw('embedding::extensions.vector <=> ?::extensions.vector ASC', [$embedding])
                ->limit(5)
                ->get();
        }

            if ($knowledgeRows->isEmpty()) {
                $knowledgeRows = $businessUnit->businessKnowledge()
                ->select('content')
                ->whereNotNull('content')
                ->limit(20)
                ->get();
            }

        $knowledgeText = $knowledgeRows->pluck('content')->implode("\n\n");

        if (trim($knowledgeText) === '') {
            return "We couldn't retrieve our direct contact details right now, but please hang tight—an agent will be with you shortly!";
        }

        $systemInstructions ??=
            "You are a helpful customer service AI for {$businessUnit->name}.\n" .
            "Answer using only the BUSINESS FACTS below. Give the official phone number, email, and operating hours when present.\n\n" .
            "BUSINESS FACTS:\n";

        // Filter knowledge text to keep only contact-relevant lines if system instructions focus on contact info
        if (stripos($systemInstructions, 'contact information') !== false) {
            $knowledgeText = $this->filterContactRelevantContent($knowledgeText);
        }

        $systemInstructions .= $knowledgeText;

        try {
            $response = Gemini::generativeModel('gemini-3.6-flash')
                ->generateContent($systemInstructions . "\n\nUSER QUESTION:\n" . $prompt);

            $rawResponse = trim((string) $response->text());
            
            // Additional fallback: filter response if it contains pricing/warranty keywords
            if (stripos($systemInstructions, 'contact information') !== false) {
                $rawResponse = $this->stripNonContactContent($rawResponse);
            }

            return $rawResponse ?: $knowledgeText;
        } catch (\Throwable $e) {
            logger()->warning('Contact response generation failed; returning retrieved knowledge.', [
                'business_unit_id' => $businessUnit->id,
                'error' => $e->getMessage(),
            ]);

            return $knowledgeText;
        }
    }

    private function markSessionHumanActive(ChatSession $session): void
    {
        $session->status = 'human_active';
        $session->handed_off_at = $session->handed_off_at ?? now();
        $session->save();
    }

    private function containsHandoffTrigger(string $message): bool
    {
        $normalized = trim($message);

        if ($normalized === '') {
            return false;
        }

        try {
            $classificationPrompt = <<<'PROMPT'
            Classify whether the customer wants to be transferred to a live human agent.
            Return valid JSON only in this exact shape:
            {"needs_human_agent": true|false}

            Customer message:
            PROMPT;

            $classificationPrompt .= "\n\n" . $normalized;

            $response = Gemini::generativeModel('gemini-3.6-flash')
                ->generateContent($classificationPrompt);

            $rawResponse = trim((string) $response->text());
            $json = $this->parseJsonObject($rawResponse);

            if (is_array($json) && array_key_exists('needs_human_agent', $json)) {
                return (bool) $json['needs_human_agent'];
            }
        } catch (\Throwable $e) {
            logger()->warning('AI handoff intent check failed; using human-language fallback.', [
                'error' => $e->getMessage(),
                'message' => $normalized,
            ]);
        }

        $lowerMessage = mb_strtolower($normalized);
        $transferIntent = preg_match(
            '/\b(?:want|need|must|require|ask|speak|talk|chat|contact|connect|get|reach|help me with)\b/i',
            $lowerMessage
        );
        $humanTarget = preg_match(
            '/\b(?:human|agent|live|support|staff|representative|person|operator|someone|customer service)\b/i',
            $lowerMessage
        );

        if ($transferIntent && $humanTarget) {
            return true;
        }

        return (bool) preg_match(
            '/(?:speak|talk|chat|contact|connect|get|reach)\s+(?:to\s+)?(?:a\s+)?(?:human|agent|live|support|staff|representative|person|operator|someone|customer service)/i',
            $lowerMessage
        );
    }

    private function parseJsonObject(string $rawResponse): ?array
    {
        $trimmed = trim($rawResponse);

        if ($trimmed === '') {
            return null;
        }

        if (str_starts_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```(?:json)?\s*/i', '', $trimmed);
            $trimmed = preg_replace('/\s*```\s*$/', '', $trimmed);
        }

        $decoded = json_decode($trimmed, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        preg_match('/\{.*\}/s', $trimmed, $matches);

        if (! isset($matches[0])) {
            return null;
        }

        $decoded = json_decode($matches[0], true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Filter knowledge text to keep only lines relevant to contact information.
     * Removes lines containing pricing, warranty, service details, etc.
     */
    private function filterContactRelevantContent(string $knowledgeText): string
    {
        $lines = explode("\n", $knowledgeText);
        $contactKeywords = ['email', 'phone', 'contact', 'hours', 'support', 'call', 'reach', 'available', 'address', 'location', 'fax', 'whatsapp'];
        $excludeKeywords = ['price', 'cost', 'warranty', 'guarantee', 'package', 'service', 'rate', 'fee', 'discount', 'offer', 'promotion', 'payment', 'financing'];

        $filteredLines = array_filter($lines, function ($line) use ($contactKeywords, $excludeKeywords) {
            $lowerLine = mb_strtolower(trim($line));

            // Skip empty lines
            if ($lowerLine === '') {
                return false;
            }

            // Exclude lines with pricing/warranty keywords
            foreach ($excludeKeywords as $keyword) {
                if (stripos($lowerLine, $keyword) !== false) {
                    return false;
                }
            }

            // Keep lines with contact keywords or lines that look like actual contact info
            $hasContactKeyword = false;
            foreach ($contactKeywords as $keyword) {
                if (stripos($lowerLine, $keyword) !== false) {
                    $hasContactKeyword = true;
                    break;
                }
            }

            // Also keep lines that look like phone numbers, emails, or addresses
            $looksLikeContactInfo = preg_match('/\+?\d{1,3}[-.\s]?\d{3,}[-.\s]?\d{3,}|[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $lowerLine);

            return $hasContactKeyword || $looksLikeContactInfo;
        });

        return implode("\n", $filteredLines);
    }

    /**
     * Strip non-contact content from LLM response as a fallback filter.
     * Ensures the response doesn't accidentally include pricing or warranty info.
     */
    private function stripNonContactContent(string $response): string
    {
        // If response contains pricing/warranty keywords, attempt to extract only contact info
        $excludeKeywords = ['price', 'cost', 'warranty', 'guarantee', 'package', 'service', 'rate', 'fee', 'discount', 'offer'];
        $lowerResponse = mb_strtolower($response);

        foreach ($excludeKeywords as $keyword) {
            if (stripos($lowerResponse, $keyword) !== false) {
                // Try to extract just the contact-relevant section
                $lines = explode("\n", $response);
                $contactLines = [];
                foreach ($lines as $line) {
                    // Skip lines with excluded keywords
                    $skip = false;
                    foreach ($excludeKeywords as $keyword) {
                        if (stripos($line, $keyword) !== false) {
                            $skip = true;
                            break;
                        }
                    }
                    if (! $skip && trim($line) !== '') {
                        $contactLines[] = $line;
                    }
                }
                return implode("\n", $contactLines);
            }
        }

        return $response;
    }

    /**
     * Cancel an active handoff request and return the session to bot_active status.
     */
    public function cancelHandoff(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer'],
        ]);

        try {
            $session = ChatSession::findOrFail($validated['session_id']);

            // Update session to bot_active and clear operator assignments
            $session->update([
                'status' => 'bot_active',
                'assigned_user_id' => null,
                'handed_off_at' => null,
            ]);

            return response()->json([
                'status' => 'cancelled',
                'message' => 'Handoff request cancelled.',
                'session_id' => $session->id,
            ]);
        } catch (\Throwable $e) {
            logger()->error('Cancel handoff failed', [
                'session_id' => $validated['session_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cancel handoff request.',
            ], 500);
        }
    }

    /**
     * Returns pgvector-formatted embedding string: "[0.123,0.456,...]"
     */
    private function embedUserPrompt(string $prompt): ?string
    {
        try {
            $response = app(\Gemini\Contracts\ClientContract::class)
                ->embeddingModel('gemini-embedding-001')
                ->embedContent(
                    $prompt,
                    \Gemini\Enums\TaskType::RETRIEVAL_QUERY,
                    null,
                    768
                );

            $values = $response->embedding->values ?? null;

            if (! is_array($values) || count($values) === 0) {
                return null;
            }

            $floatValues = array_map(
                static fn ($value): float => (float) $value,
                $values
            );

            return '[' . implode(',', $floatValues) . ']';
        } catch (\Throwable $e) {
            logger()->error('Embedding generation failed', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

//     private function embedUserPrompt(string $prompt): ?string
// {
//     $model = Gemini::embeddingModel('text-embedding-004');
//     dd(get_class_methods($model));
// }
}
