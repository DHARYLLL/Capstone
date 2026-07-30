<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Gemini\Laravel\Facades\Gemini;

use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function chat(BusinessUnit $businessUnit): View
    {
        // Hardcode business unit ID = 1 for testing
        $businessUnit = BusinessUnit::findOrFail(1);
        return view('chat', [
            'businessUnit' => $businessUnit,
        ]);
    }

    // new new new ask function with session history
    public function ask(Request $request, BusinessUnit $businessUnit): JsonResponse
    {
        // Hardcode business unit ID = 1 for testing
        $businessUnit = BusinessUnit::findOrFail(1);
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:5000'],
        ]);

        // 1. Fetch knowledge base facts
        $knowledgeBase = $businessUnit->businessKnowledge()
            ->pluck('content')
            ->implode("\n\n");

        // 2. Define System Instructions
        $systemInstructions = "You are a helpful customer service AI for this business.\n" .
            "Answer the user's question accurately using ONLY the business facts provided below.\n\n" .
            "RULES:\n" .
            "1. You ARE allowed to perform basic calculations (adding costs, quantities, discounts).\n" .
            "2. If facts do not contain the answer, politely state that you do not have that information.\n" .
            "3. Mirror the user's language/dialect and format responses clearly with bullet points where appropriate.\n\n" .
            "BUSINESS FACTS:\n" . $knowledgeBase;

        try {
            $sessionKey = "chat_history_{$businessUnit->id}";
            $rawHistory = session($sessionKey, []);

            // 3. Clean and format conversation history
            $conversationContext = "";
            if (!empty($rawHistory)) {
                $conversationContext .= "CONVERSATION HISTORY:\n";
                // Take up to last 8 messages (4 turns)
                $recentTurns = array_slice($rawHistory, -8);
                foreach ($recentTurns as $turn) {
                    $speaker = $turn['role'] === 'user' ? 'User' : 'Assistant';
                    // Strip raw quotes or escaped characters that break JSON encodings
                    $cleanText = str_replace(["\r", "\n"], " ", trim($turn['text']));
                    $conversationContext .= "{$speaker}: {$cleanText}\n";
                }
                $conversationContext .= "\n";
            }

            // 4. Construct complete prompt payload
            $fullPrompt = "{$systemInstructions}\n\n{$conversationContext}USER QUESTION:\n{$validated['prompt']}";

            // 5. Query Gemini
            $response = Gemini::generativeModel('gemini-2.5-flash')
                ->generateContent($fullPrompt);

            $rawResponse = $response->text();

            if (empty(trim($rawResponse))) {
                $rawResponse = "I couldn't process that request. Could you please rephrase?";
            }

            // 6. Save new turn to session
            $rawHistory[] = ['role' => 'user', 'text' => $validated['prompt']];
            $rawHistory[] = ['role' => 'model', 'text' => $rawResponse];

            // Keep last 8 entries in session
            session([$sessionKey => array_slice($rawHistory, -8)]);

            return response()->json([
                'message'  => $validated['prompt'],
                'response' => Str::markdown($rawResponse),
            ]);

        } catch (\Exception $e) {
            // Log the exact exception for easy inspection in storage/logs/laravel.log
            logger()->error('Gemini Chat Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'message'  => $validated['prompt'],
                'response' => "Sorry, an error occurred while generating a response.",
            ], 500);
        }
    }
}
