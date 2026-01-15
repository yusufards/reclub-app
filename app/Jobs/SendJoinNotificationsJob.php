<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendJoinNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $notificationClass;
    public $data;

    public function __construct(User $user, $notificationClass, $data)
    {
        $this->user = $user;
        $this->notificationClass = $notificationClass;
        $this->data = $data;
    }

    public function handle(): void
    {
        $this->user->notify(new $this->notificationClass($this->data));
    }
}