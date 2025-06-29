<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Notifications\UserRegisteredNotification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:user,rental'],
        ]);

        // Determine role and account status based on user type
        $role = $request->user_type === 'rental' ? 'pengusaha_rental' : 'user';

        // Check auto-activation settings
        $autoActivateUsers = Setting::get('auto_activate_user_accounts', '1') === '1';
        $autoActivateRentals = Setting::get('auto_activate_rental_accounts', '0') === '1';

        $accountStatus = 'active'; // Default

        if ($role === 'user' && !$autoActivateUsers) {
            $accountStatus = 'pending';
        } elseif ($role === 'pengusaha_rental' && !$autoActivateRentals) {
            $accountStatus = 'pending';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'account_status' => $accountStatus,
        ]);

        event(new Registered($user));

        // Send welcome notification
        $user->notify(new UserRegisteredNotification($user));

        Auth::login($user);

        // Send email verification if required
        $requireEmailVerification = Setting::get('require_email_verification', '1') === '1';
        if ($requireEmailVerification && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        // Redirect with appropriate message
        $message = 'Akun Anda berhasil dibuat.';

        if ($user->isPending()) {
            $message .= ' Akun sedang menunggu persetujuan admin dan akan diaktifkan dalam 1x24 jam.';
        }

        if ($requireEmailVerification && !$user->hasVerifiedEmail()) {
            $message .= ' Silakan periksa email Anda untuk verifikasi alamat email.';
        }

        return redirect(route('dasbor'))->with('info', $message);
    }
}
