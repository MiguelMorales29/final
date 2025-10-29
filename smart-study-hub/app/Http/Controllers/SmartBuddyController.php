<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class SmartBuddyController extends Controller
{
    public function nlp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mode' => 'required|in:answer,reviewer,flashcards,quiz',
            'content' => 'required|string',
            'question' => 'nullable|string',
        ]);

        $apiKey = config('services.openai.api_key');
        $apiUrl = rtrim(config('services.openai.api_url', 'https://api.openai.com/v1'), '/');
        $model  = config('services.openai.model', 'gpt-4o-mini');

        // Fallback if no key configured
        if (empty($apiKey)) {
            return response()->json($this->fallback($validated['mode'], $validated['content'], $validated['question'] ?? ''));
        }

        try {
            $content = $this->trimContent($validated['content']);
            $messages = $this->buildMessages($validated['mode'], $content, $validated['question'] ?? '');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl . '/chat/completions', [
                'model' => $model,
                'temperature' => 0.2,
                'messages' => $messages,
                'response_format' => $this->responseFormatFor($validated['mode']),
            ])->throw()->json();

            $text = data_get($response, 'choices.0.message.content');
            $parsed = $this->parseResponse($validated['mode'], $text);
            return response()->json($parsed);
        } catch (\Throwable $e) {
            // Graceful fallback on error
            return response()->json($this->fallback($validated['mode'], $validated['content'], $validated['question'] ?? ''), 200);
        }
    }

    private function buildMessages(string $mode, string $content, string $question): array
    {
        $system = [
            'role' => 'system',
            'content' => 'You are Smart Buddy, a concise educational assistant. Keep responses short and clear. Always follow output schema precisely.'
        ];

        switch ($mode) {
            case 'answer':
                return [
                    $system,
                    ['role' => 'user', 'content' => "Given the material, answer the question in 1-2 sentences, plain text.\n\nMaterial (trimmed):\n$content\n\nQuestion:\n$question"],
                ];
            case 'reviewer':
                return [
                    $system,
                    ['role' => 'user', 'content' => "From the material, extract up to 5 key points as concise study bullets.\n- Each item: title (3-6 words) and content (<= 140 chars).\n- Avoid duplication; cover distinct ideas.\n- Return ONLY JSON in this exact schema: {\"reviewers\":[{\"title\":string,\"content\":string}]}\n\nMaterial (trimmed):\n$content"],
                ];
            case 'flashcards':
                return [
                    $system,
                    ['role' => 'user', 'content' => "Create 6 concise Q&A flashcards from the material.\n- Prefer concept->definition, process->steps, term->purpose.\n- Avoid trivial or duplicate cards. Answers <= 160 chars.\n- Return ONLY JSON: {\"flashcards\":[{\"question\":string,\"answer\":string}]}\n\nMaterial (trimmed):\n$content"],
                ];
            case 'quiz':
                return [
                    $system,
                    ['role' => 'user', 'content' => "Create 5 multiple-choice questions (MCQs).\n- 4 short options each, only 1 correct.\n- Options must be plausible and not trivially true/false. Paraphrase the correct option; avoid copying question text.\n- Return ONLY JSON: {\"quiz\":[{\"question\":string,\"options\":[string],\"correctAnswer\":number}]}\n\nMaterial (trimmed):\n$content"],
                ];
        }

        return [$system];
    }

    private function responseFormatFor(string $mode): array
    {
        if (in_array($mode, ['reviewer','flashcards','quiz'])) {
            // Hint the model to return JSON; newer APIs may support {type:"json_object"}
            return ['type' => 'text'];
        }
        return ['type' => 'text'];
    }

    private function parseResponse(string $mode, ?string $text): array
    {
        $text = trim((string) $text);
        // Strip code fences if present
        if (preg_match('/^```json[\r\n]+([\s\S]*?)```$/', $text, $m)) {
            $text = trim($m[1]);
        } elseif (preg_match('/^```[\r\n]+([\s\S]*?)```$/', $text, $m)) {
            $text = trim($m[1]);
        }
        if (in_array($mode, ['reviewer','flashcards','quiz'])) {
            $json = $this->extractJson($text);
            if ($json) {
                $data = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'text' => null,
                        'reviewers' => $data['reviewers'] ?? [],
                        'flashcards' => $data['flashcards'] ?? [],
                        'quiz' => $data['quiz'] ?? [],
                    ];
                }
            }
        }
        return ['text' => $text];
    }

    private function trimContent(string $content, int $limit = 8000): string
    {
        $s = trim(preg_replace('/\s+/', ' ', $content));
        if (mb_strlen($s) <= $limit) return $s;
        return mb_substr($s, 0, $limit);
    }

    private function extractJson(string $text): ?string
    {
        if (preg_match('/\{[\s\S]*\}\s*$/', $text, $m)) {
            return $m[0];
        }
        if (preg_match('/\[[\s\S]*\]\s*$/', $text, $m)) {
            return $m[0];
        }
        return null;
    }

    private function fallback(string $mode, string $content, string $question): array
    {
        // Simple heuristics that mirror existing front-end behavior
        $sentences = preg_split('/(?<=[.!?])\s+/', preg_replace('/\s+/', ' ', $content));
        $sentences = array_values(array_filter($sentences, fn($s) => strlen($s) > 40));

        if ($mode === 'answer') {
            $key = $sentences[0] ?? 'a key concept';
            return ['text' => "From the module, an important idea is: $key"];        
        }
        if ($mode === 'reviewer') {
            $items = array_slice($sentences, 0, 5);
            return ['reviewers' => array_map(fn($i, $s) => ['title' => 'Key Idea ' . ($i+1), 'content' => $s], array_keys($items), $items)];
        }
        if ($mode === 'flashcards') {
            $items = array_slice($sentences, 0, 6);
            return ['flashcards' => array_map(fn($s) => ['question' => 'Explain: ' . mb_substr($s, 0, 50) . '...', 'answer' => $s], $items)];
        }
        if ($mode === 'quiz') {
            $items = array_slice($sentences, 0, 5);
            $quiz = array_map(function ($s) {
                $q = 'What best summarizes: ' . mb_substr($s, 0, 40) . '...';
                $opts = $this->createOptionsFromSentence($s);
                return [
                    'question' => $q,
                    'options' => $opts['options'],
                    'correctAnswer' => $opts['correctIndex'],
                ];
            }, $items);
            return ['quiz' => $quiz];
        }
        return ['text' => ''];
    }

    private function createOptionsFromSentence(string $sentence): array
    {
        $base = $this->cleanSummary($sentence);
        $correct = $this->paraphrase($base);
        $d1 = $this->negateMeaning($base);
        $d2 = $this->swapKeyTerms($base);
        $d3 = $this->generalizeOrOverspecify($base);

        $options = array_filter(array_map([$this, 'normalizeOption'], [$correct, $d1, $d2, $d3]));

        $options = array_values(array_unique($options));
        while (count($options) < 4) {
            $options[] = $this->normalizeOption($this->paraphrase($base . ' ' . count($options)));
        }

        $indices = range(0, count($options) - 1);
        for ($i = count($indices) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$indices[$i], $indices[$j]] = [$indices[$j], $indices[$i]];
        }
        $shuffled = array_map(fn($i) => $options[$i], $indices);
        $correctIndex = array_search(0, $indices, true);
        return ['options' => $shuffled, 'correctIndex' => $correctIndex];
    }

    private function cleanSummary(string $text): string
    {
        $s = trim(preg_replace('/\s+/', ' ', $text));
        if (preg_match('/^[^.;:]{20,160}[.;:]/', $s, $m)) $s = $m[0];
        $s = preg_replace('/^(This|That|These|Those|It|They)\s+/i', '', $s);
        return $s;
    }

    private function paraphrase(string $text): string
    {
        return preg_replace([
            '/interconnected/i', '/systems/i', '/networks/i'
        ], ['interlinked', 'platforms', 'ecosystems'], $text);
    }

    private function negateMeaning(string $text): string
    {
        if (preg_match('/\bare\b/i', $text)) return preg_replace('/\bare\b/i', 'are not', $text);
        if (preg_match('/\benables\b/i', $text)) return preg_replace('/\benables\b/i', 'does not enable', $text);
        return 'Not: ' . mb_strtolower($text);
    }

    private function swapKeyTerms(string $text): string
    {
        $replacements = [
            '/hardware/i' => 'databases',
            '/software/i' => 'protocols',
            '/clients?/i' => 'servers',
            '/servers?/i' => 'clients',
            '/network(s)?/i' => 'applications',
            '/web/i' => 'local',
        ];
        $s = $text;
        foreach ($replacements as $pattern => $to) {
            $s = preg_replace($pattern, $to, $s);
        }
        return $s;
    }

    private function generalizeOrOverspecify(string $text): string
    {
        if (mb_strlen($text) > 90) return preg_replace('/\b(are|is)\b/i', 'can be', $text);
        return 'A high-level overview with unrelated specifics about storage layers';
    }

    private function normalizeOption(string $s): string
    {
        $out = trim(preg_replace('/\s+/', ' ', $s));
        if (mb_strlen($out) > 140) $out = mb_substr($out, 0, 137) . '...';
        return $out;
    }
}


