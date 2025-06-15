<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class AccountDeletedNotification extends Notification
{
    use Queueable;

    protected $userData;

    /**
     * Create a new notification instance.
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
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
        // Get email template
        $template = Setting::get('email_template_account_deleted',
            '<h2>Konfirmasi Penghapusan Akun - CekPenyewa.com</h2><p>Halo {{name}},</p><p>Akun Anda di CekPenyewa.com telah berhasil dihapus sesuai permintaan Anda.</p><p>Terima kasih telah menggunakan layanan CekPenyewa.com.</p>'
        );

        // Replace template variables
        $content = str_replace([
            '{{name}}',
            '{{email}}',
            '{{date}}'
        ], [
            $this->userData['name'],
            $this->userData['email'],
            now()->format('d/m/Y H:i')
        ], $template);

        return (new MailMessage)
            ->subject('Konfirmasi Penghapusan Akun - CekPenyewa.com')
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
            'user_data' => $this->userData,
            'deleted_at' => now()
        ];
    }
}
