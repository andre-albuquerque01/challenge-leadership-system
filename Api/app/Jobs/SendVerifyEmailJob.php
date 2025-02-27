<?php

namespace App\Jobs;

use App\Events\VerifyEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVerifyEmailJob implements ShouldQueue
{
    use Queueable;

    public string $email, $token, $id;
    /**
     * Create a new event instance.
     */
    public function __construct(string $email, string $token, string $id)
    {
        $this->email = $email;
        $this->token = $token;
        $this->id = $id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new VerifyEmailEvent($this->email, $this->token, $this->id));
    }
}
