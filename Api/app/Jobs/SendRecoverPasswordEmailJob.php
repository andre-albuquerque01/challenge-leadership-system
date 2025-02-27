<?php

namespace App\Jobs;

use App\Events\RecoverPasswordEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendRecoverPasswordEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email, public string $token)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new RecoverPasswordEmailEvent($this->email, $this->token));
    }
}
