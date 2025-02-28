<?php

namespace App\Providers;

use App\Events\RecoverPasswordEmailEvent;
use App\Events\VerifyEmailEvent;
use App\Listeners\SendEmailRecoverPasswordListener;
use App\Listeners\SendEmailRegisteredListener;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        RecoverPasswordEmailEvent::class => [
            SendEmailRecoverPasswordListener::class
        ],
        VerifyEmailEvent::class => [
            SendEmailRegisteredListener::class
        ]
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
