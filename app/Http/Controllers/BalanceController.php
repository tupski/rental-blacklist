<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function history(Request $request)
    {
        $user = Auth::user();
        $currentBalance = $user->getCurrentBalance();

        $query = $user->balanceTransactions()->latest();

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // For AJAX requests
        if ($request->ajax()) {
            $transactions = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'has_more' => $transactions->hasMorePages()
                ]
            ]);
        }

        $transactions = $query->paginate(10);

        // Statistics
        $stats = [
            'total_topup' => $user->balanceTransactions()->where('type', 'topup')->sum('amount'),
            'total_usage' => $user->balanceTransactions()->where('type', 'usage')->sum('amount'),
            'total_refund' => $user->balanceTransactions()->where('type', 'refund')->sum('amount'),
            'total_this_month' => $user->balanceTransactions()
                                      ->whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->sum('amount'),
        ];

        return view('balance.history', compact('currentBalance', 'transactions', 'stats'));
    }

    public function exportPDF(Request $request)
    {
        $user = Auth::user();

        $query = $user->balanceTransactions()->latest();

        // Apply same filters as history
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->get();

        // Calculate totals for filtered data
        $totals = [
            'total_amount' => $transactions->sum('amount'),
            'total_topup' => $transactions->where('type', 'topup')->sum('amount'),
            'total_usage' => $transactions->where('type', 'usage')->sum('amount'),
            'total_refund' => $transactions->where('type', 'refund')->sum('amount'),
        ];

        $filters = [
            'type' => $request->type,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        $pdf = \PDF::loadView('balance.export-pdf', compact('user', 'transactions', 'totals', 'filters'));

        $filename = 'riwayat-transaksi-' . $user->id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
