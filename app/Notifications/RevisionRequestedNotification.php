<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class RevisionRequestedNotification extends Notification
{
    use Queueable;

    protected $revisionNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct($revisionNotes)
    {
        $this->revisionNotes = $revisionNotes;
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
        $template = Setting::get('email_template_revision_requested', 
            'Halo {{name}},<br><br>' .
            'Akun rental Anda di CekPenyewa.com memerlukan revisi data pendaftaran.<br><br>' .
            '<strong>Catatan dari Admin:</strong><br>' .
            '{{revision_notes}}<br><br>' .
            'Silakan login ke akun Anda dan perbarui data pendaftaran sesuai catatan di atas:<br>' .
            '<a href="{{revision_url}}">{{revision_url}}</a><br><br>' .
            'Setelah revisi selesai, tim kami akan memverifikasi kembali dalam 1-3 hari kerja.<br><br>' .
            'Jika ada pertanyaan, silakan hubungi kami di {{admin_contact}}.<br><br>' .
            'Terima kasih,<br>' .
            'Tim CekPenyewa.com'
        );

        // Replace template variables
        $revisionUrl = route('daftar.revisi');
        $adminContact = Setting::get('admin_contact_wa1', '0819-1191-9993');
        
        $content = str_replace([
            '{{name}}',
            '{{email}}',
            '{{revision_notes}}',
            '{{revision_url}}',
            '{{admin_contact}}',
            '{{date}}'
        ], [
            $notifiable->name,
            $notifiable->email,
            $this->revisionNotes,
            $revisionUrl,
            $adminContact,
            now()->format('d/m/Y H:i')
        ], $template);

        return (new MailMessage)
            ->subject('Revisi Data Pendaftaran Diperlukan - CekPenyewa.com')
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
            'message' => 'Data pendaftaran Anda memerlukan revisi.',
            'revision_notes' => $this->revisionNotes,
            'requested_at' => now()
        ];
    }
}
