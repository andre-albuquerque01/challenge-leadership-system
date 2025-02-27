<?php

namespace App\Listeners;

use App\Events\VerifyEmailEvent;
use App\Mail\VerifyEmailMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailRegisteredListener
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
    public function handle(VerifyEmailEvent $event): void
    {
        Mail::to($event->email)->send(new VerifyEmailMail([
            'toEmail' => $event->email,
            'subject' => 'Verificação de e-mail',
            'message' => $event->id,
            'token' => $event->token
        ]));
    }
}
