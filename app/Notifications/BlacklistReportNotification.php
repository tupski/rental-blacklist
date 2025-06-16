<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\GuestReport;

class BlacklistReportNotification extends Notification
{
    use Queueable;

    protected $guestReport;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(GuestReport $guestReport, string $action = 'created')
    {
        $this->guestReport = $guestReport;
        $this->action = $action; // 'created', 'approved', 'rejected'
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $actionText = $this->getActionText();
        
        return (new MailMessage)
                    ->line("Laporan blacklist telah {$actionText}.")
                    ->action('Lihat Detail', route('admin.laporan-tamu.tampil', $this->guestReport->id))
                    ->line('Silakan review dan ambil tindakan yang diperlukan.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $actionText = $this->getActionText();
        $color = $this->getActionColor();
        $icon = $this->getActionIcon();

        return [
            'type' => 'blacklist_report',
            'title' => 'Laporan Blacklist',
            'message' => "Laporan blacklist untuk {$this->guestReport->nama_lengkap} telah {$actionText}",
            'report_id' => $this->guestReport->id,
            'reporter_name' => $this->guestReport->nama_pelapor,
            'reported_name' => $this->guestReport->nama_lengkap,
            'rental_type' => $this->guestReport->jenis_rental,
            'status' => $this->guestReport->status,
            'action' => $this->action,
            'url' => route('admin.laporan-tamu.tampil', $this->guestReport->id),
            'icon' => $icon,
            'color' => $color
        ];
    }

    private function getActionText(): string
    {
        return match($this->action) {
            'created' => 'dibuat',
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            default => 'diperbarui'
        };
    }

    private function getActionColor(): string
    {
        return match($this->action) {
            'created' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    private function getActionIcon(): string
    {
        return match($this->action) {
            'created' => 'fas fa-plus-circle',
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            default => 'fas fa-info-circle'
        };
    }
}
