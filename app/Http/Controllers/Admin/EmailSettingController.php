<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class EmailSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::whereIn('group', ['smtp', 'email_templates'])
            ->orderBy('group')
            ->orderBy('label')
            ->get()
            ->groupBy('group');

        return view('admin.settings.email', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Pengaturan Email berhasil disimpan!');
    }

    public function testSmtp(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Get current SMTP settings
            $smtpHost = Setting::get('smtp_host', $request->smtp_host);
            $smtpPort = Setting::get('smtp_port', $request->smtp_port);
            $smtpUsername = Setting::get('smtp_username', $request->smtp_username);
            $smtpPassword = Setting::get('smtp_password', $request->smtp_password);
            $smtpEncryption = Setting::get('smtp_encryption', $request->smtp_encryption);
            $mailFromAddress = Setting::get('mail_from_address', $request->mail_from_address);
            $mailFromName = Setting::get('mail_from_name', $request->mail_from_name);

            // Set temporary mail configuration
            Config::set('mail.mailers.smtp.host', $smtpHost);
            Config::set('mail.mailers.smtp.port', $smtpPort);
            Config::set('mail.mailers.smtp.username', $smtpUsername);
            Config::set('mail.mailers.smtp.password', $smtpPassword);
            Config::set('mail.mailers.smtp.encryption', $smtpEncryption);
            Config::set('mail.from.address', $mailFromAddress);
            Config::set('mail.from.name', $mailFromName);

            // Get test email template
            $testTemplate = Setting::get('email_template_test', 'Ini adalah email test dari sistem CekPenyewa.com. Jika Anda menerima email ini, berarti konfigurasi SMTP sudah benar.');

            // Send test email
            Mail::raw($testTemplate, function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email - CekPenyewa.com');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email berhasil dikirim ke ' . $request->test_email . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim test email: ' . $e->getMessage()
            ]);
        }
    }
}
