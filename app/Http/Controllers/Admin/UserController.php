<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
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
            'role' => 'required|in:user,pengusaha_rental,admin',
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

        return redirect()->route('admin.users.index')
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
            'role' => 'required|in:user,pengusaha_rental',
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

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()
                ->with('error', 'Admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        // Implementasi toggle status jika diperlukan
        return redirect()->back()
            ->with('success', 'Status user berhasil diubah.');
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
