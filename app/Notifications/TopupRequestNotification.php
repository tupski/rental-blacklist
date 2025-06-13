<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopupRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $topupRequest;
    protected $type; // 'created', 'confirmed', 'rejected'

    /**
     * Create a new notification instance.
     */
    public function __construct($topupRequest, $type = 'created')
    {
        $this->topupRequest = $topupRequest;
        $this->type = $type;
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
        $message = new MailMessage;

        switch ($this->type) {
            case 'created':
                $message->subject('Permintaan Topup Berhasil Dibuat')
                       ->greeting('Halo ' . $this->topupRequest->user->name . '!')
                       ->line('Permintaan topup Anda telah berhasil dibuat.')
                       ->line('**Detail Topup:**')
                       ->line('Invoice: ' . $this->topupRequest->invoice_number)
                       ->line('Jumlah: ' . $this->topupRequest->formatted_amount)
                       ->line('Metode: ' . ucfirst($this->topupRequest->payment_method))
                       ->line('Status: ' . $this->topupRequest->status_text);

                if ($this->topupRequest->payment_method === 'manual') {
                    $message->line('**Instruksi Pembayaran:**')
                           ->line('Silakan transfer ke rekening yang telah dipilih.')
                           ->line('Setelah transfer, upload bukti pembayaran di dashboard Anda.');
                }

                $message->action('Lihat Detail', route('topup.show', $this->topupRequest->id));
                break;

            case 'confirmed':
                $message->subject('Topup Berhasil Dikonfirmasi')
                       ->greeting('Halo ' . $this->topupRequest->user->name . '!')
                       ->line('Topup Anda telah berhasil dikonfirmasi!')
                       ->line('**Detail Topup:**')
                       ->line('Invoice: ' . $this->topupRequest->invoice_number)
                       ->line('Jumlah: ' . $this->topupRequest->formatted_amount)
                       ->line('Saldo telah ditambahkan ke akun Anda.')
                       ->action('Lihat Saldo', route('balance.history'));
                break;

            case 'rejected':
                $message->subject('Topup Ditolak')
                       ->greeting('Halo ' . $this->topupRequest->user->name . '!')
                       ->line('Maaf, permintaan topup Anda ditolak.')
                       ->line('**Detail Topup:**')
                       ->line('Invoice: ' . $this->topupRequest->invoice_number)
                       ->line('Jumlah: ' . $this->topupRequest->formatted_amount);

                if ($this->topupRequest->admin_notes) {
                    $message->line('**Alasan:** ' . $this->topupRequest->admin_notes);
                }

                $message->line('Silakan hubungi customer service untuk informasi lebih lanjut.')
                       ->action('Buat Topup Baru', route('topup.create'));
                break;
        }

        return $message->salutation('Salam hangat, Tim ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'topup_id' => $this->topupRequest->id,
            'invoice_number' => $this->topupRequest->invoice_number,
            'amount' => $this->topupRequest->amount,
            'type' => $this->type,
            'message' => $this->getArrayMessage()
        ];
    }

    private function getArrayMessage()
    {
        switch ($this->type) {
            case 'created':
                return 'Permintaan topup berhasil dibuat';
            case 'confirmed':
                return 'Topup berhasil dikonfirmasi';
            case 'rejected':
                return 'Permintaan topup ditolak';
            default:
                return 'Update topup';
        }
    }
}
