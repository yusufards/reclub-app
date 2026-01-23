<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Room;
use App\Channels\WhatsAppChannel;

class RoomCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $room;

    /**
     * Create a new notification instance.
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Bisa tambahkan 'mail' atau 'database' jika perlu
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        $sportName = $this->room->sport->name;
        $venueName = $this->room->venue->name;
        $date = $this->room->start_datetime->format('d M H:i');
        $url = route('rooms.show', $this->room->id);

        return "📢 INFO MABAR BARU! \n\n" .
            "Ada room *{$sportName}* baru di sekitarmu!\n\n" .
            "🏟 Venue: {$venueName}\n" .
            "📅 Waktu: {$date}\n" .
            "👤 Host: {$this->room->host->name}\n\n" .
            "Yuk gabung sekarang sebelum penuh! 👇\n" .
            " $url "; // Spasi tambahan agar dikenali sebagai link
    }
}
