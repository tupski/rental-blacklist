<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RentalRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notifications\AccountSuspendedNotification;
use App\Notifications\RevisionRequestedNotification;

class RentalAccountController extends Controller
{
    /**
     * Display rental accounts management page
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = User::where('role', 'pengusaha_rental')
                    ->with('rentalRegistration')
                    ->when($status !== 'all', function ($q) use ($status) {
                        return $q->where('account_status', $status);
                    })
                    ->when($search, function ($q) use ($search) {
                        return $q->where(function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhereHas('rentalRegistration', function ($regQuery) use ($search) {
                                        $regQuery->where('nama_rental', 'like', "%{$search}%");
                                    });
                        });
                    })
                    ->orderBy('created_at', 'desc');

        $accounts = $query->paginate(10)->appends($request->query());

        $statusCounts = [
            'all' => User::where('role', 'pengusaha_rental')->count(),
            'active' => User::where('role', 'pengusaha_rental')->where('account_status', 'active')->count(),
            'pending' => User::where('role', 'pengusaha_rental')->where('account_status', 'pending')->count(),
            'needs_revision' => User::where('role', 'pengusaha_rental')->where('account_status', 'needs_revision')->count(),
            'suspended' => User::where('role', 'pengusaha_rental')->where('account_status', 'suspended')->count(),
        ];

        return view('admin.rental-accounts.index', compact('accounts', 'statusCounts', 'status', 'search'));
    }

    /**
     * Show rental account details
     */
    public function show(User $account)
    {
        if ($account->role !== 'pengusaha_rental') {
            abort(404);
        }

        $account->load('rentalRegistration');

        return view('admin.rental-accounts.show', compact('account'));
    }

    /**
     * Approve rental account
     */
    public function approve(User $account)
    {
        if ($account->role !== 'pengusaha_rental') {
            return response()->json(['error' => 'Invalid account type'], 400);
        }

        if ($account->account_status === 'active') {
            return response()->json(['error' => 'Account is already active'], 400);
        }

        $account->approve(Auth::id());

        return response()->json([
            'success' => true,
            'message' => "Akun {$account->name} berhasil diaktifkan."
        ]);
    }

    /**
     * Request revision for rental account
     */
    public function requestRevision(Request $request, User $account)
    {
        $request->validate([
            'revision_notes' => 'required|string|max:1000'
        ]);

        if ($account->role !== 'pengusaha_rental') {
            return response()->json(['error' => 'Invalid account type'], 400);
        }

        $account->requestRevision($request->revision_notes, Auth::id());

        // Send notification email
        try {
            $account->notify(new RevisionRequestedNotification($request->revision_notes));
        } catch (\Exception $e) {
            \Log::warning('Failed to send revision request notification: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Permintaan revisi berhasil dikirim ke {$account->name}."
        ]);
    }

    /**
     * Suspend rental account
     */
    public function suspend(Request $request, User $account)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:1000',
            'suspension_type' => 'required|in:permanent,temporary',
            'suspension_days' => 'required_if:suspension_type,temporary|nullable|integer|min:1|max:365'
        ]);

        if ($account->role !== 'pengusaha_rental') {
            return response()->json(['error' => 'Invalid account type'], 400);
        }

        if ($account->role === 'admin') {
            return response()->json(['error' => 'Cannot suspend admin account'], 400);
        }

        $account->suspend(
            $request->suspension_reason,
            $request->suspension_type,
            $request->suspension_days,
            Auth::id()
        );

        // Send notification email
        try {
            $account->notify(new AccountSuspendedNotification(
                $request->suspension_reason,
                $request->suspension_type,
                $request->suspension_days
            ));
        } catch (\Exception $e) {
            \Log::warning('Failed to send account suspension notification: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Akun {$account->name} berhasil dibekukan."
        ]);
    }

    /**
     * Reactivate suspended account
     */
    public function reactivate(User $account)
    {
        if ($account->role !== 'pengusaha_rental') {
            return response()->json(['error' => 'Invalid account type'], 400);
        }

        if ($account->account_status !== 'suspended') {
            return response()->json(['error' => 'Account is not suspended'], 400);
        }

        $account->update([
            'account_status' => 'active',
            'suspension_reason' => null,
            'suspension_type' => null,
            'suspension_days' => null,
            'suspended_at' => null,
            'suspension_ends_at' => null,
            'suspended_by' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => "Akun {$account->name} berhasil diaktifkan kembali."
        ]);
    }
}
