<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
            // Get current SMTP settings from form or database
            $smtpHost = $request->smtp_host ?: Setting::get('smtp_host');
            $smtpPort = $request->smtp_port ?: Setting::get('smtp_port');
            $smtpUsername = $request->smtp_username ?: Setting::get('smtp_username');
            $smtpPassword = $request->smtp_password ?: Setting::get('smtp_password');
            $smtpEncryption = $request->smtp_encryption ?: Setting::get('smtp_encryption');
            $mailFromAddress = $request->mail_from_address ?: Setting::get('mail_from_address');
            $mailFromName = $request->mail_from_name ?: Setting::get('mail_from_name');

            // Log SMTP settings for debugging
            Log::info('SMTP Test Settings:', [
                'host' => $smtpHost,
                'port' => $smtpPort,
                'username' => $smtpUsername,
                'encryption' => $smtpEncryption,
                'from_address' => $mailFromAddress,
                'from_name' => $mailFromName,
                'test_email' => $request->test_email
            ]);

            // Validate required settings
            if (empty($smtpHost) || empty($smtpPort) || empty($smtpUsername) || empty($smtpPassword)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi SMTP tidak lengkap. Pastikan Host, Port, Username, dan Password sudah diisi.'
                ]);
            }

            // Set temporary mail configuration
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $smtpHost);
            Config::set('mail.mailers.smtp.port', (int)$smtpPort);
            Config::set('mail.mailers.smtp.username', $smtpUsername);
            Config::set('mail.mailers.smtp.password', $smtpPassword);
            Config::set('mail.mailers.smtp.encryption', $smtpEncryption);
            Config::set('mail.from.address', $mailFromAddress);
            Config::set('mail.from.name', $mailFromName);

            // Clear mail manager to force reconfiguration
            app()->forgetInstance('mail.manager');

            // Get test email template
            $testTemplate = Setting::get('email_template_test', 'Ini adalah email test dari sistem CekPenyewa.com. Jika Anda menerima email ini, berarti konfigurasi SMTP sudah benar.');

            // Send test email using raw method with HTML
            Mail::raw($testTemplate, function ($message) use ($request, $mailFromAddress, $mailFromName) {
                $message->to($request->test_email)
                        ->subject('Test Email - CekPenyewa.com')
                        ->from($mailFromAddress, $mailFromName);
            });

            Log::info('Test email sent successfully to: ' . $request->test_email);

            // Check if we're using log driver
            $originalMailer = config('mail.default');
            $message = 'Test email berhasil dikirim ke ' . $request->test_email . '!';

            if ($originalMailer === 'log') {
                $message .= ' (CATATAN: MAIL_MAILER=log di .env, jadi email hanya disimpan di log file storage/logs/laravel.log. Untuk mengirim email sungguhan, ubah MAIL_MAILER=smtp di .env)';
            } else {
                $message .= ' Email telah dikirim melalui SMTP.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'test_email' => $request->test_email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim test email: ' . $e->getMessage()
            ]);
        }
    }
}
