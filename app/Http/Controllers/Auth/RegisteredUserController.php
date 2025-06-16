<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Services\AI\ContentModerationService;
use App\Models\AiModerationLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Support\Facades\Notification;

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

        // AI Moderation for registration
        $moderationService = new ContentModerationService();
        $moderation = $moderationService->moderateContent(
            'registration',
            $user->id,
            $request->name . ' ' . ($request->bio ?? '')
        );

        // Update moderation log with correct content ID
        AiModerationLog::where('content_type', 'registration')
                      ->where('content_id', 0)
                      ->latest()
                      ->first()
                      ?->update(['content_id' => $user->id]);

        // Apply AI decision
        if ($moderation['decision'] === 'reject') {
            $user->update(['account_status' => 'suspended']);
            $accountStatus = 'suspended';
        } elseif ($moderation['decision'] === 'flag') {
            $user->update(['account_status' => 'pending']);
            $accountStatus = 'pending';
        }

        event(new Registered($user));

        // Send welcome notification
        $user->notify(new UserRegisteredNotification($user));

        // Send notification to admins about new user registration
        try {
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new UserRegisteredNotification($user));
        } catch (\Exception $e) {
            \Log::warning('Failed to send user registration notification to admins: ' . $e->getMessage());
        }

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
