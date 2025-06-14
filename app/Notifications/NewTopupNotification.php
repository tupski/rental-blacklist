<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TopupRequest;

class NewTopupNotification extends Notification
{
    use Queueable;

    protected $topup;

    /**
     * Create a new notification instance.
     */
    public function __construct(TopupRequest $topup)
    {
        $this->topup = $topup;
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
        return (new MailMessage)
                    ->line('Ada permintaan topup baru yang perlu diverifikasi.')
                    ->action('Lihat Detail', route('admin.isi-saldo.tampil', $this->topup->id))
                    ->line('Silakan verifikasi pembayaran dan approve/reject permintaan topup.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_topup',
            'title' => 'Permintaan Topup Baru',
            'message' => "Permintaan topup sebesar Rp " . number_format($this->topup->amount, 0, ',', '.') . " dari {$this->topup->user->name}",
            'topup_id' => $this->topup->id,
            'user_name' => $this->topup->user->name,
            'amount' => $this->topup->amount,
            'payment_method' => $this->topup->payment_method,
            'status' => $this->topup->status,
            'url' => route('admin.isi-saldo.tampil', $this->topup->id),
            'icon' => 'fas fa-credit-card',
            'color' => 'info'
        ];
    }
}
