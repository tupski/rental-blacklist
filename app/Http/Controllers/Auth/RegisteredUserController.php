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
            'responsible_name' => ['required', 'string', 'max:255'],
            'responsible_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'responsible_position' => ['required', 'string'],
            'responsible_phone' => ['required', 'string', 'max:15'],
            'entity_type' => ['required', 'string'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['required', 'string'],
            'company_phone' => ['nullable', 'string', 'max:15'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'legal_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        // Only rental owners are allowed
        $role = 'pengusaha_rental';

        // Check auto-activation settings for rental accounts
        $autoActivateRentals = Setting::get('auto_activate_rental_accounts', '0') === '1';
        $accountStatus = $autoActivateRentals ? 'active' : 'pending';

        // Handle legal document upload
        $legalDocumentPath = null;
        if ($request->hasFile('legal_document')) {
            $file = $request->file('legal_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $legalDocumentPath = $file->storeAs('legal-documents', $filename, 'public');
        }

        $user = User::create([
            'name' => $request->responsible_name,
            'email' => $request->responsible_email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'account_status' => $accountStatus,
            'no_hp' => $request->responsible_phone,
            'alamat' => $request->company_address,
        ]);

        // Create rental registration record
        $user->rentalRegistration()->create([
            'nama_rental' => $request->company_name,
            'jenis_rental' => ['Umum'], // Default, can be updated later
            'alamat' => $request->company_address,
            'no_hp' => $request->company_phone ?? $request->responsible_phone,
            'email' => $request->company_email ?? $request->responsible_email,
            'nama_pemilik' => $request->responsible_name,
            'no_hp_pemilik' => $request->responsible_phone,
            'dokumen_legalitas' => $legalDocumentPath ? [$legalDocumentPath] : [],
            'status' => $accountStatus === 'active' ? 'approved' : 'pending',
            'user_id' => $user->id,
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
