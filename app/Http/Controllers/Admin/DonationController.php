<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('donor_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('donor_type')) {
            $query->where('donor_type', $request->get('donor_type'));
        }

        $donations = $query->latest()->paginate(10);

        $statistics = [
            'total' => Donation::count(),
            'pending' => Donation::where('status', 'pending')->count(),
            'confirmed' => Donation::where('status', 'confirmed')->count(),
            'total_amount' => Donation::where('status', 'confirmed')->sum('amount'),
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.donations.partials.table', compact('donations'))->render(),
                'pagination_html' => view('admin.donations.partials.pagination', compact('donations'))->render(),
                'statistics' => $statistics
            ]);
        }

        return view('admin.donations.index', compact('donations', 'statistics'));
    }

    public function show(Donation $donation)
    {
        return view('admin.donations.show', compact('donation'));
    }

    public function confirm(Donation $donation)
    {
        $donation->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id()
        ]);

        return redirect()->back()
            ->with('success', 'Donasi berhasil dikonfirmasi');
    }

    public function reject(Donation $donation)
    {
        $donation->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()
            ->with('success', 'Donasi berhasil ditolak');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->route('admin.donasi.indeks')
            ->with('success', 'Donasi berhasil dihapus');
    }
}
