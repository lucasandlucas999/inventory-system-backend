<?php

namespace App\Domains\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model = 'gemini-2.0-flash';
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Stream a chat response from Gemini, yielding parsed text chunks.
     *
     * @param array $messages  [['role' => 'user'|'assistant', 'content' => string], ...]
     * @param string $systemContext
     * @return \Generator<string>
     */
    public function streamChat(array $messages, string $systemContext = ''): \Generator
    {
        $url = "{$this->baseUrl}/{$this->model}:streamGenerateContent?key={$this->apiKey}&alt=sse";

        $payload = $this->buildPayload($messages, $systemContext);

        $response = Http::withOptions([
                'stream' => true,
                'verify' => false, // Laragon local dev — remove in production
            ])
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body'   => $errorBody,
            ]);
            $decoded  = json_decode($errorBody, true);
            $detail   = $decoded['error']['message'] ?? "HTTP {$response->status()}";
            throw new \RuntimeException("Gemini: {$detail}");
        }

        $body   = $response->getBody();
        $buffer = '';

        while (!$body->eof()) {
            $buffer .= $body->read(1024);

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line   = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);

                if (!str_starts_with($line, 'data: ') || $line === 'data: [DONE]') {
                    continue;
                }

                $data = json_decode(substr($line, 6), true);
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text !== null && $text !== '') {
                    yield $text;
                }
            }
        }
    }

    private function buildPayload(array $messages, string $systemContext): array
    {
        $contents = array_map(fn($m) => [
            'role'  => $m['role'] === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $m['content']]],
        ], $messages);

        $payload = [
            'contents'         => $contents,
            'generationConfig' => [
                'maxOutputTokens' => 2048,
                'temperature'     => 0.7,
            ],
        ];

        if ($systemContext) {
            $payload['systemInstruction'] = [
                'parts' => [['text' => $systemContext]],
            ];
        }

        return $payload;
    }
}
