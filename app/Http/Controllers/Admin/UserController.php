<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AccountBannedNotification;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        if ($request->filled('email_status')) {
            if ($request->get('email_status') === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(10)->appends($request->query());

        // Get statistics for all users (not just filtered)
        $allUsers = User::where('role', '!=', 'admin')->get();
        $statistics = [
            'total' => $allUsers->count(),
            'pengusaha_rental' => $allUsers->where('role', 'pengusaha_rental')->count(),
            'email_verified' => $allUsers->whereNotNull('email_verified_at')->count(),
            'email_unverified' => $allUsers->whereNull('email_verified_at')->count(),
        ];

        // Handle AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.users.partials.table', compact('users'))->render(),
                'pagination_html' => view('admin.users.partials.pagination', compact('users'))->render(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ]
            ]);
        }

        return view('admin.users.index', compact('users', 'statistics'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:pengusaha_rental,admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ];

        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        }

        $user = User::create($userData);

        // Send notification if requested
        if ($request->has('send_notification')) {
            $user->notify(new \App\Notifications\UserRegisteredNotification($user, $request->password));
        }

        return redirect()->route('admin.pengguna.indeks')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:pengusaha_rental',
            'nik' => 'nullable|string|max:16',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $updateData = $request->only(['name', 'email', 'role', 'nik', 'no_hp', 'alamat']);

        // Handle email verification status
        if ($request->has('email_verified')) {
            $updateData['email_verified_at'] = now();
        } else {
            $updateData['email_verified_at'] = null;
        }

        // Handle password reset
        if ($request->has('reset_password')) {
            $updateData['password'] = Hash::make('password123');
        }

        $user->update($updateData);

        return redirect()->route('admin.pengguna.tampil', $user)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()
                ->with('error', 'Admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.pengguna.indeks')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        // Implementasi toggle status jika diperlukan
        return redirect()->back()
            ->with('success', 'Status user berhasil diubah.');
    }

    public function ban(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($user->role === 'admin') {
            return redirect()->back()
                ->with('error', 'Admin tidak dapat dibanned.');
        }

        if ($user->isBanned()) {
            return redirect()->back()
                ->with('error', 'User sudah dalam status banned.');
        }

        // Ban the user
        $user->ban($request->reason, Auth::id());

        // Send notification email
        try {
            $user->notify(new AccountBannedNotification($request->reason, Auth::user()->name));
        } catch (\Exception $e) {
            // Log error but don't fail the ban process
            \Log::error('Failed to send ban notification email: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', "User {$user->name} berhasil dibanned.");
    }

    public function unban(User $user)
    {
        if (!$user->isBanned()) {
            return redirect()->back()
                ->with('error', 'User tidak dalam status banned.');
        }

        // Unban the user
        $user->unban();

        return redirect()->back()
            ->with('success', "User {$user->name} berhasil di-unban.");
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'password123';
        $user->update(['password' => Hash::make($newPassword)]);

        return redirect()->back()
            ->with('success', 'Password berhasil direset ke: ' . $newPassword);
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }
}
