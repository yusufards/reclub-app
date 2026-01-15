<?php

namespace App\Mail;

use App\Models\RoomParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL; // <--- PENTING: Library untuk membuat Link Ajaib

class RoomJoinRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $urlConfirm; // <--- Variabel baru untuk menampung link konfirmasi

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(RoomParticipant $participant)
    {
        $this->participant = $participant;

        // --- GENERATE SIGNED URL (MAGIC LINK) ---
        // Link ini aman karena ada "tanda tangan" digitalnya.
        // Link ini akan mengarah ke route 'participants.confirm_email' yang kita buat di web.php
        // Valid selama 3 hari.
        
        $this->urlConfirm = URL::temporarySignedRoute(
            'participants.confirm_email', // Nama route
            now()->addDays(3),            // Waktu kadaluarsa
            ['participant' => $participant->id] // Parameter ID peserta
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Saya ubah Subject-nya agar lebih menarik perhatian Host
        return $this->subject('⚡ Action Needed: Permintaan Join Room ' . $this->participant->room->title)
                    ->view('emails.join_request');
    }
}