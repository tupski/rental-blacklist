<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class AccountBannedNotification extends Notification
{
    use Queueable;

    protected $reason;
    protected $bannedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($reason, $bannedBy = null)
    {
        $this->reason = $reason;
        $this->bannedBy = $bannedBy;
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
        $template = Setting::get('email_template_account_suspended',
            '<h2>Akun Ditangguhkan - CekPenyewa.com</h2><p>Halo {{name}},</p><p>Kami informasikan bahwa akun Anda di CekPenyewa.com telah ditangguhkan sementara.</p><p><strong>Alasan:</strong> {{reason}}</p><p>Jika Anda merasa ini adalah kesalahan atau ingin mengajukan banding, silakan hubungi tim support kami.</p><p>Terima kasih atas pengertian Anda.</p>'
        );

        // Replace template variables
        $adminContact = Setting::get('admin_contact_wa1', '0819-1191-9993');
        $content = str_replace([
            '{{name}}',
            '{{email}}',
            '{{reason}}',
            '{{date}}',
            '{{admin_contact}}'
        ], [
            $notifiable->name,
            $notifiable->email,
            $this->reason,
            now()->format('d/m/Y H:i'),
            $adminContact
        ], $template);

        return (new MailMessage)
            ->subject('Akun Ditangguhkan - CekPenyewa.com')
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
            'reason' => $this->reason,
            'banned_by' => $this->bannedBy,
            'banned_at' => now()
        ];
    }
}
