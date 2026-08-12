<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Gemini\Laravel\Facades\Gemini;

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

    // public function ask(Request $request, BusinessUnit $businessUnit): JsonResponse
    // {
    //     // 1. Validate request payload including incoming user_id
    //     $validated = $request->validate([
    //         'prompt'  => ['required', 'string', 'max:5000'],
    //         'user_id' => ['required', 'string', 'max:255'],
    //     ]);

    //     $prompt = $validated['prompt'];
    //     $userId = $validated['user_id'];

    //     // 2. Fetch knowledge base facts for this business unit
    //     $knowledgeBase = $businessUnit->businessKnowledge()
    //         ->pluck('content')
    //         ->implode("\n\n");

    //     // 3. System Instructions
    //     $systemInstructions = "You are a helpful customer service AI for this business.\n" .
    //         "Answer the user's question accurately using ONLY the business facts provided below.\n\n" .
    //         "RULES:\n" .
    //         "1. You ARE allowed to perform basic calculations (adding costs, quantities, discounts).\n" .
    //         "2. If facts do not contain the answer, politely state that you do not have that information.\n" .
    //         "3. Mirror the user's language/dialect and format responses clearly with bullet points where appropriate.\n\n" .
    //         "BUSINESS FACTS:\n" . $knowledgeBase;

    //     try {
    //         // 4. Retrieve chat history from Cache (Keyed by business unit + unique user ID)
    //         $cacheKey = "chat_history_{$businessUnit->id}_{$userId}";
    //         $rawHistory = Cache::get($cacheKey, []);

    //         // 5. Clean and format conversation context
    //         $conversationContext = "";
    //         if (!empty($rawHistory)) {
    //             $conversationContext .= "CONVERSATION HISTORY:\n";
    //             $recentTurns = array_slice($rawHistory, -8); // Keep last 8 entries (4 back-and-forth turns)
    //             foreach ($recentTurns as $turn) {
    //                 $speaker = $turn['role'] === 'user' ? 'User' : 'Assistant';
    //                 $cleanText = str_replace(["\r", "\n"], " ", trim($turn['text']));
    //                 $conversationContext .= "{$speaker}: {$cleanText}\n";
    //             }
    //             $conversationContext .= "\n";
    //         }

    //         // 6. Build prompt payload
    //         $fullPrompt = "{$systemInstructions}\n\n{$conversationContext}USER QUESTION:\n{$prompt}";

    //         // 7. Query Gemini
    //         $response = Gemini::generativeModel('gemini-2.5-flash')
    //             ->generateContent($fullPrompt);

    //         $rawResponse = $response->text();

    //         if (empty(trim($rawResponse))) {
    //             $rawResponse = "I couldn't process that request. Could you please rephrase?";
    //         }

    //         // 8. Append new turn and save back to Cache for 24 hours
    //         $rawHistory[] = ['role' => 'user', 'text' => $prompt];
    //         $rawHistory[] = ['role' => 'model', 'text' => $rawResponse];

    //         Cache::put($cacheKey, array_slice($rawHistory, -8), now()->addHours(24));

    //         return response()->json([
    //             'message'  => $prompt,
    //             'response' => Str::markdown($rawResponse),
    //         ]);

    //     } catch (\Exception $e) {
    //         logger()->error('Gemini Chat Error: ' . $e->getMessage(), [
    //             'exception' => $e
    //         ]);

    //         return response()->json([
    //             'message'  => $prompt,
    //             'response' => "Sorry, an error occurred while generating a response.",
    //         ], 500);
    //     }
    // }

    public function ask(Request $request, BusinessUnit $businessUnit): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:5000'],
        ]);

        $promptText = $validated['prompt'];

        // 1. Embed user query using Gemini embedding model
        $queryEmbedding = $this->embedUserPrompt($promptText);

        // 2. Vector search: fetch top 5 relevant chunks
        if ($queryEmbedding) {
            $relevantChunks = $businessUnit->businessKnowledge()
                ->select('content')
                ->orderByRaw('embedding <=> ?::vector', [$queryEmbedding])
                ->limit(5)
                ->pluck('content');

            $knowledgeBase = $relevantChunks->implode("\n\n");
        } else {
            // Fallback if embedding fails
            $knowledgeBase = $businessUnit->businessKnowledge()
                ->limit(5)
                ->pluck('content')
                ->implode("\n\n");
        }

        // 3. System Instruction
        $systemInstructions = "You are a helpful customer service AI for this business.\n" .
            "Answer the user's question accurately using ONLY the business facts provided below.\n\n" .
            "RULES:\n" .
            "1. You ARE allowed to perform basic calculations (adding costs, quantities, discounts).\n" .
            "2. If facts do not contain the answer, politely state that you do not have that information.\n" .
            "3. Mirror the user's language/dialect and format responses clearly with bullet points where appropriate.\n\n" .
            "BUSINESS FACTS:\n" . ($knowledgeBase ?: 'No relevant knowledge found.');

        try {
            $sessionKey = "chat_history_{$businessUnit->id}";
            $rawHistory = session($sessionKey, []);

            // 4. Conversation History (last 8 turns)
            $conversationContext = "";
            if (!empty($rawHistory)) {
                $conversationContext .= "CONVERSATION HISTORY:\n";
                $recentTurns = array_slice($rawHistory, -8);
                foreach ($recentTurns as $turn) {
                    $speaker = $turn['role'] === 'user' ? 'User' : 'Assistant';
                    $cleanText = str_replace(["\r", "\n"], " ", trim($turn['text']));
                    $conversationContext .= "{$speaker}: {$cleanText}\n";
                }
                $conversationContext .= "\n";
            }

            // 5. Query Gemini 2.5
            $fullPrompt = "{$systemInstructions}\n\n{$conversationContext}USER QUESTION:\n{$promptText}";

            $response = Gemini::generativeModel('gemini-2.5-flash')
                ->generateContent($fullPrompt);

            $rawResponse = $response->text();

            if (empty(trim($rawResponse))) {
                $rawResponse = "I couldn't process that request. Could you please rephrase?";
            }

            // 6. Save chat session history
            $rawHistory[] = ['role' => 'user', 'text' => $promptText];
            $rawHistory[] = ['role' => 'model', 'text' => $rawResponse];
            session([$sessionKey => array_slice($rawHistory, -8)]);

            return response()->json([
                'message'  => $promptText,
                'response' => Str::markdown($rawResponse),
            ]);

        } catch (\Exception $e) {
            logger()->error('Gemini Chat Error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message'  => $promptText,
                'response' => "Sorry, an error occurred while generating a response.",
            ], 500);
        }
    }

    /**
     * Generate 768-dim vector embedding for incoming user questions.
     */
    private function embedUserPrompt(string $prompt): ?string
    {
        $baseUrl = rtrim((string) (config('gemini.base_url') ?: 'https://generativelanguage.googleapis.com/v1beta'), '/');

        $response = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->timeout(10)
            ->withQueryParameters(['key' => (string) config('gemini.api_key')])
            ->post('models/text-embedding-004:embedContent', [
                'model' => 'models/text-embedding-004',
                'content' => [
                    'parts' => [['text' => $prompt]],
                ],
                'task_type' => 'RETRIEVAL_QUERY',
                'output_dimensionality' => 768,
            ]);

        if (!$response->successful()) {
            return null;
        }

        $values = $response->json('embedding.values');

        return is_array($values) ? '[' . implode(',', $values) . ']' : null;
    }
}
