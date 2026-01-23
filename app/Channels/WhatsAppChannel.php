<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $to = $notifiable->phone;

        if (!$to) {
            Log::warning("WhatsAppChannel: No phone number for user " . $notifiable->id);
            return;
        }

        $provider = env('WA_PROVIDER', 'http');

        try {
            if ($provider === 'twilio') {
                $this->sendTwilio($to, $message);
            } elseif ($provider === 'wa_cloud') {
                $this->sendMeta($to, $message);
            } elseif ($provider === 'fonte') {
                $this->sendFonte($to, $message);
            } else {
                // Generic HTTP/Log for dev
                Log::info("WA_STUB ($to): $message");
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Failed: " . $e->getMessage());
        }
    }

    protected function sendTwilio($to, $message)
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM');

        Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json", [
                'From' => $from,
                'To' => "whatsapp:$to",
                'Body' => $message,
            ]);
    }

    protected function sendMeta($to, $message)
    {
        $phoneId = env('WA_CLOUD_PHONE_ID');
        $token = env('WA_CLOUD_TOKEN');

        Http::withToken($token)
            ->post("https://graph.facebook.com/v17.0/$phoneId/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => ['body' => $message]
            ]);
    }

    protected function sendFonte($to, $message)
    {
        $token = env('FONNTE_TOKEN');
        $url = env('FONNTE_URL', 'https://api.fonnte.com/send');

        Log::info("WhatsAppChannel: Sending to $to via Fonte...");

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post($url, [
                    'target' => $to,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

        Log::info("WhatsAppChannel: Fonte Response: " . $response->body());
    }
}