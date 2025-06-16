<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\ChatbotConversation;
use App\Services\AI\AiManager;
use Illuminate\Http\Request;

class AiProviderController extends Controller
{
    private AiManager $aiManager;

    public function __construct(AiManager $aiManager)
    {
        $this->aiManager = $aiManager;
    }

    /**
     * Display AI providers management
     */
    public function index()
    {
        $providers = AiProvider::orderBy('priority')->get();
        $stats = $this->aiManager->getProviderStats();
        $analytics = ChatbotConversation::getAnalytics(30);

        return view('admin.ai-providers.index', compact('providers', 'stats', 'analytics'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.ai-providers.create');
    }

    /**
     * Store new AI provider
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|in:claude,openai,gemini',
            'display_name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'endpoint' => 'required|url',
            'model' => 'required|string|max:255',
            'daily_limit' => 'required|integer|min:1',
            'monthly_limit' => 'required|integer|min:1',
            'priority' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        try {
            AiProvider::create($request->all());

            return redirect()->route('admin.ai-providers.index')
                ->with('success', 'AI Provider berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan AI Provider: ' . $e->getMessage()]);
        }
    }

    /**
     * Show edit form
     */
    public function edit(AiProvider $aiProvider)
    {
        return view('admin.ai-providers.edit', compact('aiProvider'));
    }

    /**
     * Update AI provider
     */
    public function update(Request $request, AiProvider $aiProvider)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'endpoint' => 'required|url',
            'model' => 'required|string|max:255',
            'daily_limit' => 'required|integer|min:1',
            'monthly_limit' => 'required|integer|min:1',
            'priority' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        try {
            $aiProvider->update($request->all());

            return redirect()->route('admin.ai-providers.index')
                ->with('success', 'AI Provider berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui AI Provider: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete AI provider
     */
    public function destroy(AiProvider $aiProvider)
    {
        try {
            $aiProvider->delete();

            return redirect()->route('admin.ai-providers.index')
                ->with('success', 'AI Provider berhasil dihapus');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus AI Provider: ' . $e->getMessage()]);
        }
    }

    /**
     * Test AI provider connection
     */
    public function test(AiProvider $aiProvider)
    {
        try {
            $response = $this->aiManager->testProvider($aiProvider);

            if ($response->isSuccess()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test berhasil! Provider dapat digunakan.',
                    'response' => $response->getContent(),
                    'response_time' => $response->responseTimeMs . 'ms',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $response->getError(),
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Test gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset usage counters
     */
    public function resetUsage(AiProvider $aiProvider)
    {
        try {
            $aiProvider->update([
                'daily_usage' => 0,
                'monthly_usage' => 0,
                'last_reset_daily' => now(),
                'last_reset_monthly' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usage counter berhasil direset'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal reset usage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle provider status
     */
    public function toggleStatus(AiProvider $aiProvider)
    {
        try {
            $aiProvider->update(['is_active' => !$aiProvider->is_active]);

            $status = $aiProvider->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => "Provider berhasil {$status}",
                'is_active' => $aiProvider->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time stats
     */
    public function getStats()
    {
        try {
            $stats = $this->aiManager->getProviderStats();
            $analytics = ChatbotConversation::getAnalytics(1); // Today only

            return response()->json([
                'success' => true,
                'providers' => $stats,
                'analytics' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
