<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class AccountApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $template = Setting::get('email_template_account_approved', 
            'Selamat {{name}}!<br><br>' .
            'Akun rental Anda di CekPenyewa.com telah disetujui dan diaktifkan.<br><br>' .
            'Anda sekarang dapat:<br>' .
            '• Mengakses dashboard rental<br>' .
            '• Mencari data blacklist pelanggan<br>' .
            '• Membuat laporan blacklist<br>' .
            '• Menggunakan API CekPenyewa<br><br>' .
            'Silakan login ke akun Anda: <a href="{{login_url}}">{{login_url}}</a><br><br>' .
            'Terima kasih telah bergabung dengan CekPenyewa.com!<br><br>' .
            'Salam,<br>' .
            'Tim CekPenyewa.com'
        );

        // Replace template variables
        $loginUrl = route('masuk');
        $content = str_replace([
            '{{name}}',
            '{{email}}',
            '{{login_url}}',
            '{{date}}'
        ], [
            $notifiable->name,
            $notifiable->email,
            $loginUrl,
            now()->format('d/m/Y H:i')
        ], $template);

        return (new MailMessage)
            ->subject('Akun Disetujui - CekPenyewa.com')
            ->view('emails.template', ['content' => $content]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Akun rental Anda telah disetujui dan diaktifkan.',
            'approved_at' => now()
        ];
    }
}
