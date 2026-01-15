<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\WhatsAppChannel;

class JoinConfirmedNotification extends Notification
{
    use Queueable;
    public $room;

    public function __construct($room) { $this->room = $room; }

    public function via($notifiable) {
        return ['mail', 'database', WhatsAppChannel::class];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('Room Join Confirmed!')
            ->line('You have been confirmed for: ' . $this->room->title)
            ->action('View Room', url('/rooms/' . $this->room->id));
    }

    public function toWhatsApp($notifiable) {
        return "Your request to join '{$this->room->title}' has been confirmed! See you there.";
    }

    public function toArray($notifiable) {
        return [
            'room_id' => $this->room->id,
            'message' => 'Join request confirmed for ' . $this->room->title
        ];
    }
}