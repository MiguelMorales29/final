<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SmartBuddyController extends Controller
{
    public function nlp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mode' => 'required|in:answer,reviewer,flashcards,quiz',
            'content' => 'required|string',
            'question' => 'nullable|string',
            'studentName' => 'nullable|string',
            'dateTime' => 'nullable|string',
        ]);

        $studentName = isset($validated['studentName']) ? $validated['studentName'] : 'there';
        $firstName = explode(' ', $studentName)[0];
        
        $dateTime = isset($validated['dateTime']) ? $validated['dateTime'] : Carbon::now()->format('l, F d, Y g:i A');
        
        $apiKey = config('services.groq.api_key');
        $apiUrl = rtrim(config('services.groq.api_url', 'https://api.groq.com/openai/v1'), '/');
        $model = config('services.groq.model', 'llama-3.1-8b-instant');

        if (empty($apiKey)) {
            $question = isset($validated['question']) ? $validated['question'] : '';
            return response()->json($this->fallback($validated['mode'], $validated['content'], $question, $firstName));
        }

        try {
            $content = $this->trimContent($validated['content']);
            $question = isset($validated['question']) ? $validated['question'] : '';
            $messages = $this->buildMessages(
                $validated['mode'], 
                $content, 
                $question, 
                $firstName,
                $dateTime
            );

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ])
                ->throw()
                ->json();

            $text = data_get($response, 'choices.0.message.content');
            $parsed = $this->parseResponse($validated['mode'], $text);
            
            return response()->json($parsed);
        } catch (\Throwable $e) {
            \Log::error('Smart Buddy API Error: ' . $e->getMessage());
            $question = isset($validated['question']) ? $validated['question'] : '';
            return response()->json($this->fallback($validated['mode'], $validated['content'], $question, $firstName), 200);
        }
    }

    private function buildMessages($mode, $content, $question, $firstName, $dateTime)
    {
        $systemPrompt = $this->buildSystemPrompt($firstName, $dateTime, $mode);
        
        switch($mode) {
            case 'answer':
                $userMessage = $this->buildAnswerPrompt($content, $question);
                break;
            case 'reviewer':
                $userMessage = $this->buildReviewerPrompt($content);
                break;
            case 'flashcards':
                $userMessage = $this->buildFlashcardsPrompt($content);
                break;
            case 'quiz':
                $userMessage = $this->buildQuizPrompt($content);
                break;
            default:
                $userMessage = $this->buildAnswerPrompt($content, $question);
        }

        return [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userMessage],
        ];
    }

    private function buildSystemPrompt($firstName, $dateTime, $mode)
    {
        $greeting = $this->getTimeBasedGreeting($firstName);
        
        $basePrompt = "You are Smart Buddy, a friendly, encouraging AI study companion helping students learn from course modules.\n\n";
        $basePrompt .= "CURRENT CONTEXT:\n";
        $basePrompt .= "- Student's Name: {$firstName}\n";
        $basePrompt .= "- Current Date/Time: {$dateTime}\n";
        $basePrompt .= "- Greeting to use: {$greeting}\n\n";
        $basePrompt .= "PERSONALITY & BEHAVIOR:\n";
        $basePrompt .= "- ALWAYS use the student's first name ({$firstName}) when responding\n";
        $basePrompt .= "- Be warm, friendly, conversational (never robotic)\n";
        $basePrompt .= "- Show enthusiasm and encouragement\n";
        $basePrompt .= "- Use emojis naturally\n";
        $basePrompt .= "- Celebrate progress and understanding\n\n";
        $basePrompt .= "STRICT BOUNDARY:\n";
        $basePrompt .= "- You ONLY have access to the specific module content provided\n";
        $basePrompt .= "- If asked about topics outside the module, politely redirect\n";
        $basePrompt .= "- NEVER answer questions outside the provided module content\n\n";

        $modePrompts = [
            'answer' => "MODE: CONVERSATIONAL CHAT\n- Answer questions about the module conversationally\n- Use 2-4 paragraphs typically\n- Return plain text response",
            'reviewer' => "MODE: STUDY POINTS GENERATOR\n- Generate EXACTLY 5 key study points\n- Return ONLY valid JSON: {\"reviewers\":[{\"title\":\"...\",\"content\":\"...\"}]}",
            'flashcards' => "MODE: FLASHCARD GENERATOR\n- Generate EXACTLY 6 flashcards\n- Return ONLY valid JSON: {\"flashcards\":[{\"question\":\"...\",\"answer\":\"...\"}]}",
            'quiz' => "MODE: QUIZ GENERATOR\n- Generate EXACTLY 5 MCQs with 4 plausible options each\n- ALL options must be related to module\n- Return ONLY valid JSON: {\"quiz\":[{\"question\":\"...\",\"options\":[...],\"correctAnswer\":0}]}"
        ];

        return $basePrompt . $modePrompts[$mode];
    }

    private function getTimeBasedGreeting($firstName)
    {
        $hour = Carbon::now()->hour;
        
        if ($hour >= 5 && $hour < 12) {
            return "Good morning, {$firstName}!";
        } elseif ($hour >= 12 && $hour < 17) {
            return "Good afternoon, {$firstName}!";
        } elseif ($hour >= 17 && $hour < 21) {
            return "Good evening, {$firstName}!";
        } else {
            return "Hey there, {$firstName}! Burning the midnight oil?";
        }
    }

    private function buildAnswerPrompt($content, $question)
    {
        return "MODULE CONTENT:\n{$content}\n\nSTUDENT QUESTION:\n{$question}\n\nPlease answer using ONLY the module content.";
    }

    private function buildReviewerPrompt($content)
    {
        return "MODULE CONTENT:\n{$content}\n\nGenerate exactly 5 key study points. Return JSON only.";
    }

    private function buildFlashcardsPrompt($content)
    {
        return "MODULE CONTENT:\n{$content}\n\nGenerate exactly 6 flashcards. Return JSON only.";
    }

    private function buildQuizPrompt($content)
    {
        return "MODULE CONTENT:\n{$content}\n\nGenerate exactly 5 MCQs with plausible options. Return JSON only.";
    }

    private function parseResponse($mode, $text)
    {
        $text = trim((string) $text);
        
        if (preg_match('/```json\s*([\s\S]*?)\s*```/', $text, $m)) {
            $text = trim($m[1]);
        } elseif (preg_match('/```\s*([\s\S]*?)\s*```/', $text, $m)) {
            $text = trim($m[1]);
        }

        if (in_array($mode, ['reviewer', 'flashcards', 'quiz'])) {
            $json = $this->extractJson($text);
            if ($json) {
                $data = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'text' => null,
                        'reviewers' => isset($data['reviewers']) ? $data['reviewers'] : [],
                        'flashcards' => isset($data['flashcards']) ? $data['flashcards'] : [],
                        'quiz' => isset($data['quiz']) ? $data['quiz'] : [],
                    ];
                }
            }
        }

        return ['text' => $text];
    }

    private function trimContent($content, $limit = 8000)
    {
        $s = trim(preg_replace('/\s+/', ' ', $content));
        if (mb_strlen($s) <= $limit) return $s;
        return mb_substr($s, 0, $limit) . '...';
    }

    private function extractJson($text)
    {
        if (preg_match('/\{[\s\S]*\}\s*$/m', $text, $m)) {
            return $m[0];
        }
        return null;
    }

    private function fallback($mode, $content, $question, $firstName)
    {
        $greeting = $this->getTimeBasedGreeting($firstName);
        $sentences = preg_split('/[.!?]/', $content);
        $sentences = array_filter($sentences, function($s) { return strlen(trim($s)) > 40; });
        $sentences = array_values($sentences);

        if ($mode === 'answer') {
            $key = isset($sentences[0]) ? $sentences[0] : 'a key concept';
            return ['text' => "{$greeting} Based on the module: {$key}"];
        }
        
        if ($mode === 'reviewer') {
            $reviewers = [];
            $items = array_slice($sentences, 0, 5);
            foreach ($items as $i => $s) {
                $reviewers[] = [
                    'title' => 'Key Idea ' . ($i + 1),
                    'content' => mb_substr(trim($s), 0, 140)
                ];
            }
            return ['reviewers' => $reviewers];
        }
        
        if ($mode === 'flashcards') {
            $flashcards = [];
            $items = array_slice($sentences, 0, 6);
            foreach ($items as $s) {
                $flashcards[] = [
                    'question' => 'Explain: ' . mb_substr(trim($s), 0, 50) . '...',
                    'answer' => mb_substr(trim($s), 0, 200)
                ];
            }
            return ['flashcards' => $flashcards];
        }
        
        if ($mode === 'quiz') {
            $quiz = [];
            $items = array_slice($sentences, 0, 5);
            foreach ($items as $i => $s) {
                $correct = mb_substr(trim($s), 0, 100);
                $quiz[] = [
                    'question' => 'Question ' . ($i + 1) . ': What does this describe?',
                    'options' => [$correct, 'Alternative A', 'Alternative B', 'Alternative C'],
                    'correctAnswer' => 0
                ];
            }
            return ['quiz' => $quiz];
        }

        return ['text' => ''];
    }
}
