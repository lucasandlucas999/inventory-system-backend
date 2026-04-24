<?php

namespace App\Domains\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    private string $apiKey;
    private string $model   = 'llama-3.3-70b-versatile';
    private string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
    }

    /**
     * @param array  $messages  [['role' => 'user'|'assistant', 'content' => string], ...]
     * @param string $systemContext
     * @return \Generator<string>
     */
    public function streamChat(array $messages, string $systemContext = ''): \Generator
    {
        $payload = $this->buildPayload($messages, $systemContext);

        $response = Http::withOptions(['stream' => true, 'verify' => false])
            ->withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ])
            ->post($this->baseUrl, $payload);

        if (!$response->successful()) {
            $decoded = json_decode($response->body(), true);
            $detail  = $decoded['error']['message'] ?? "HTTP {$response->status()}";
            Log::error('Groq API error', ['status' => $response->status(), 'detail' => $detail]);
            throw new \RuntimeException("Groq: {$detail}");
        }

        $body   = $response->getBody();
        $buffer = '';

        while (!$body->eof()) {
            $buffer .= $body->read(1024);

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line   = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);

                if (!str_starts_with($line, 'data: ')) continue;

                $payload = substr($line, 6);
                if ($payload === '[DONE]') return;

                $data = json_decode($payload, true);
                $text = $data['choices'][0]['delta']['content'] ?? null;

                if ($text !== null && $text !== '') {
                    yield $text;
                }
            }
        }
    }

    private function buildPayload(array $messages, string $systemContext): array
    {
        $contents = [];

        if ($systemContext) {
            $contents[] = ['role' => 'system', 'content' => $systemContext];
        }

        foreach ($messages as $m) {
            $contents[] = ['role' => $m['role'], 'content' => $m['content']];
        }

        return [
            'model'       => $this->model,
            'messages'    => $contents,
            'stream'      => true,
            'temperature' => 0.7,
            'max_tokens'  => 2048,
        ];
    }
}
