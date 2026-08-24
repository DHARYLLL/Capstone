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
                    // If you added a 'slug' column to business_units:
                    // $q->orWhere('slug', $businessParam);
                }
            });
        })
        ->first() ?? BusinessUnit::first(); // 👈 Uses first available record instead of 404ing

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

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'customer',
            'message_text' => $promptText,
        ]);

        if ($session->status === 'human_active') {
            return response()->json([
                'status' => 'success',
                'session_id' => $session->id,
            ]);
        }

        $normalized = mb_strtolower($promptText);
        $isGreetingOnly = (bool) preg_match(
            '/^(hi|hello|hey|good morning|good afternoon|good evening|yo|hola)[!,.?\\s]*$/iu',
            $normalized
        );

        if ($isGreetingOnly) {
            $greetingReply = "Hello. Welcome to {$businessUnit->name}. How can I help you today?";

            ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'bot',
                'message_text' => $greetingReply,
            ]);

            return response()->json([
                'status' => 'bot_active',
                'message' => $promptText,
                'response' => Str::markdown($greetingReply),
                'session_id' => $session->id,
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

            ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_type' => 'bot',
                'message_text' => $rawResponse,
            ]);

            return response()->json([
                'status' => 'bot_active',
                'message' => $promptText,
                'response' => Str::markdown($rawResponse),
                'session_id' => $session->id,
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
            ], 500);
        }
    }

    public function getMessages(int $sessionId): JsonResponse
    {
        $session = ChatSession::findOrFail($sessionId);

        return response()->json([
            'status' => $session->status,
            'messages' => $session->chatMessages()->oldest('created_at')->get(),
        ]);
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
