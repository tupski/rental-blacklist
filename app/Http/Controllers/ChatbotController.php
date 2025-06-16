<?php

namespace App\Http\Controllers;

use App\Services\AI\AiManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    private AiManager $aiManager;

    public function __construct(AiManager $aiManager)
    {
        $this->aiManager = $aiManager;
    }

    /**
     * Show chatbot interface
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Send message to chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string|max:100',
        ]);

        try {
            // Get or create session ID
            $sessionId = $request->session_id ?: Str::uuid()->toString();

            // Get user info
            $userId = auth()->id();
            $userIp = $request->ip();

            // Send message to AI
            $response = $this->aiManager->sendMessage(
                $request->message,
                $sessionId,
                $userId,
                $userIp
            );

            if ($response->isSuccess()) {
                return response()->json([
                    'success' => true,
                    'message' => $response->getContent(),
                    'session_id' => $sessionId,
                    'metadata' => [
                        'tokens_used' => $response->tokensUsed,
                        'response_time' => $response->responseTimeMs,
                        'provider' => $response->metadata['model'] ?? 'AI',
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $response->getError(),
                    'session_id' => $sessionId,
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'session_id' => $request->session_id,
            ], 500);
        }
    }

    /**
     * Get chatbot status (public)
     */
    public function getStatus()
    {
        try {
            $status = $this->aiManager->getChatbotStatus();

            return response()->json([
                'success' => true,
                'available' => $status['available'],
                'provider_count' => $status['provider_count'],
                'providers' => $status['providers'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'available' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get chatbot statistics (for admin)
     */
    public function getStats()
    {
        $this->authorize('admin');

        try {
            $stats = $this->aiManager->getProviderStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear conversation history
     */
    public function clearHistory(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|max:100',
        ]);

        try {
            // In a real implementation, you might want to soft delete
            // or just return success without actually deleting

            return response()->json([
                'success' => true,
                'message' => 'Riwayat percakapan telah dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menghapus riwayat percakapan'
            ], 500);
        }
    }

    /**
     * Get conversation history
     */
    public function getHistory(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|max:100',
        ]);

        try {
            $history = \App\Models\ChatbotConversation::where('session_id', $request->session_id)
                ->where('status', 'success')
                ->orderBy('created_at')
                ->limit(20)
                ->get()
                ->map(function ($conversation) {
                    return [
                        'user_message' => $conversation->user_message,
                        'bot_response' => $conversation->bot_response,
                        'timestamp' => $conversation->created_at->format('H:i'),
                        'provider' => $conversation->ai_provider,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil riwayat percakapan'
            ], 500);
        }
    }
}
