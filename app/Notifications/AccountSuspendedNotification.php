<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class AccountSuspendedNotification extends Notification
{
    use Queueable;

    protected $reason;
    protected $type;
    protected $days;

    /**
     * Create a new notification instance.
     */
    public function __construct($reason, $type, $days = null)
    {
        $this->reason = $reason;
        $this->type = $type;
        $this->days = $days;
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
        $template = Setting::get('email_template_account_suspended', 
            'Halo {{name}},<br><br>' .
            'Akun rental Anda di CekPenyewa.com telah dibekukan {{suspension_type}}.<br><br>' .
            '<strong>Alasan Pembekuan:</strong><br>' .
            '{{reason}}<br><br>' .
            '{{duration_info}}<br><br>' .
            'Selama masa pembekuan, Anda tidak dapat mengakses fitur-fitur berikut:<br>' .
            '• Dashboard rental<br>' .
            '• Pencarian data blacklist<br>' .
            '• Pembuatan laporan<br>' .
            '• Akses API<br><br>' .
            'Jika Anda merasa pembekuan ini tidak tepat, silakan hubungi admin di {{admin_contact}}.<br><br>' .
            'Terima kasih,<br>' .
            'Tim CekPenyewa.com'
        );

        // Prepare suspension info
        $suspensionType = $this->type === 'permanent' ? 'secara permanen' : 'sementara';
        $durationInfo = $this->type === 'temporary' && $this->days 
            ? "Pembekuan akan berakhir dalam {$this->days} hari dari sekarang."
            : 'Pembekuan ini bersifat permanen.';

        // Replace template variables
        $adminContact = Setting::get('admin_contact_wa1', '0819-1191-9993');
        
        $content = str_replace([
            '{{name}}',
            '{{email}}',
            '{{reason}}',
            '{{suspension_type}}',
            '{{duration_info}}',
            '{{admin_contact}}',
            '{{date}}'
        ], [
            $notifiable->name,
            $notifiable->email,
            $this->reason,
            $suspensionType,
            $durationInfo,
            $adminContact,
            now()->format('d/m/Y H:i')
        ], $template);

        return (new MailMessage)
            ->subject('Akun Dibekukan - CekPenyewa.com')
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
            'message' => 'Akun rental Anda telah dibekukan.',
            'reason' => $this->reason,
            'type' => $this->type,
            'days' => $this->days,
            'suspended_at' => now()
        ];
    }
}
