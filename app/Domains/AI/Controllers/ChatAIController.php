<?php

namespace App\Domains\AI\Controllers;

use App\Domains\AI\Actions\ChatWithAIAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatAIController extends Controller
{
    public function __invoke(Request $request, ChatWithAIAction $action): StreamedResponse
    {
        return response()->stream(function () use ($request, $action) {
            try {
                foreach ($action->execute($request) as $text) {
                    echo 'data: ' . json_encode(['text' => $text]) . "\n\n";
                    ob_flush();
                    flush();
                }

                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
            } catch (\RuntimeException $e) {
                Log::warning('AI Chat action error', ['message' => $e->getMessage()]);
                echo 'data: ' . json_encode(['error' => $e->getMessage()]) . "\n\n";
                ob_flush();
                flush();
            } catch (\Exception $e) {
                Log::error('AI Chat unexpected error', ['message' => $e->getMessage()]);
                echo 'data: ' . json_encode(['error' => 'Error inesperado en el asistente IA']) . "\n\n";
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }
}
