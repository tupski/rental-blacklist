<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\RentalRegistration;

class RevisionSubmittedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $registration;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, RentalRegistration $registration)
    {
        $this->user = $user;
        $this->registration = $registration;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Revisi data pendaftaran dari {$this->user->name} telah dikirim dan menunggu review.",
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'rental_name' => $this->registration->nama_rental,
            'submitted_at' => now(),
            'action_url' => route('admin.rental-accounts.show', $this->user->id)
        ];
    }
}
