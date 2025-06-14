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
        if ($request->has('type') && $request->jenis) {
            $query->where('type', $request->jenis);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        // Statistics
        $stats = [
            'total_topup' => $user->balanceTransactions()->where('type', 'topup')->sum('amount'),
            'total_usage' => $user->balanceTransactions()->where('type', 'usage')->sum('amount'),
            'total_refund' => $user->balanceTransactions()->where('type', 'refund')->sum('amount'),
            'this_month_usage' => $user->balanceTransactions()
                                      ->where('type', 'usage')
                                      ->whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->sum('amount'),
        ];

        return view('balance.history', compact('currentBalance', 'transactions', 'stats'));
    }
}
