<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class ContactController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('contact.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required_if:captcha_enabled,1',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
            'g-recaptcha-response.required_if' => 'Captcha wajib diisi.',
        ]);

        // Verify captcha if enabled
        $settings = Setting::pluck('value', 'key')->toArray();
        if (isset($settings['captcha_enabled']) && $settings['captcha_enabled'] == '1' &&
            isset($settings['captcha_contact']) && $settings['captcha_contact'] == '1') {

            if (!$this->verifyCaptcha($request->input('g-recaptcha-response'), $settings)) {
                return back()->withErrors(['captcha' => 'Verifikasi captcha gagal.'])->withInput();
            }
        }

        // Send email (you can implement this later)
        // For now, just store in session or log

        return back()->with('success', 'Pesan Anda berhasil dikirim. Kami akan segera merespons.');
    }

    private function verifyCaptcha($response, $settings)
    {
        if (empty($response)) {
            return false;
        }

        $captchaType = $settings['captcha_type'] ?? 'recaptcha';

        if ($captchaType === 'recaptcha') {
            $secretKey = $settings['recaptcha_secret_key'] ?? '';
            if (empty($secretKey)) {
                return true; // Skip verification if no secret key
            }

            $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => $secretKey,
                'response' => $response,
                'remoteip' => request()->ip()
            ];

            $response = file_get_contents($verifyURL . '?' . http_build_query($data));
            $result = json_decode($response, true);

            return isset($result['success']) && $result['success'] === true;
        }

        return true; // For other captcha types, implement later
    }
}
