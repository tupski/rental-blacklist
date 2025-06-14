<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TopupRequest;

class TopupStatusNotification extends Notification
{
    use Queueable;

    protected $topup;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(TopupRequest $topup, string $action)
    {
        $this->topup = $topup;
        $this->action = $action; // 'approved', 'rejected', 'pending_confirmation'
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
        $statusText = $this->getStatusText();
        
        return (new MailMessage)
                    ->line("Permintaan topup telah {$statusText}.")
                    ->action('Lihat Detail', route('admin.isi-saldo.tampil', $this->topup->id))
                    ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusText = $this->getStatusText();
        $color = $this->getStatusColor();
        $icon = $this->getStatusIcon();

        return [
            'type' => 'topup_status_update',
            'title' => 'Update Status Topup',
            'message' => "Topup Rp " . number_format($this->topup->amount, 0, ',', '.') . " dari {$this->topup->user->name} telah {$statusText}",
            'topup_id' => $this->topup->id,
            'user_name' => $this->topup->user->name,
            'amount' => $this->topup->amount,
            'status' => $this->topup->status,
            'action' => $this->action,
            'url' => route('admin.isi-saldo.tampil', $this->topup->id),
            'icon' => $icon,
            'color' => $color
        ];
    }

    private function getStatusText(): string
    {
        return match($this->action) {
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'pending_confirmation' => 'menunggu konfirmasi',
            default => 'diperbarui'
        };
    }

    private function getStatusColor(): string
    {
        return match($this->action) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending_confirmation' => 'warning',
            default => 'info'
        };
    }

    private function getStatusIcon(): string
    {
        return match($this->action) {
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            'pending_confirmation' => 'fas fa-clock',
            default => 'fas fa-info-circle'
        };
    }
}
