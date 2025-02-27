<?php

namespace App\Listeners;

use App\Events\RecoverPasswordEmailEvent;
use App\Mail\RecoverPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailRecoverPasswordListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecoverPasswordEmailEvent $event): void
    {
        Mail::to($event->email)->send(new RecoverPasswordMail([
            'toEmail' => $event->email,
            'subject' => 'Recuperação de senha',
            'token' => $event->token
        ]));
    }
}
