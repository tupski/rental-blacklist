<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $password = null)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Send email to the user, database notification to admins
        if ($notifiable->id === $this->user->id) {
            return ['mail']; // User gets email
        } else {
            return ['database']; // Admins get database notification
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Selamat Datang di ' . config('app.name'))
            ->greeting('Halo ' . $this->user->name . '!')
            ->line('Terima kasih telah mendaftar di ' . config('app.name') . '.')
            ->line('Akun Anda telah berhasil dibuat dengan detail berikut:')
            ->line('**Email:** ' . $this->user->email)
            ->line('**Role:** ' . ucfirst(str_replace('_', ' ', $this->user->role)));

        if ($this->password) {
            $message->line('**Password:** ' . $this->password)
                   ->line('⚠️ **Penting:** Silakan ganti password Anda setelah login pertama kali.');
        }

        $message->action('Login Sekarang', route('masuk'))
               ->line('Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.')
               ->line('Terima kasih telah bergabung dengan kami!')
               ->salutation('Salam hangat, Tim ' . config('app.name'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $roleText = $this->user->role === 'pengusaha_rental' ? 'Pemilik Rental' : 'User';
        $statusText = $this->user->account_status === 'pending' ? ' (Menunggu Persetujuan)' : '';

        return [
            'type' => 'user_registration',
            'title' => 'Pendaftaran User Baru',
            'message' => "{$roleText} baru '{$this->user->name}' telah mendaftar{$statusText}",
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'user_role' => $this->user->role,
            'account_status' => $this->user->account_status,
            'url' => $this->user->account_status === 'pending'
                ? route('admin.persetujuan-akun.indeks')
                : route('admin.pengguna.tampil', $this->user->id),
            'icon' => 'fas fa-user-plus',
            'color' => $this->user->account_status === 'pending' ? 'warning' : 'success'
        ];
    }
}
