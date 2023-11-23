<?php

namespace App\Providers;

use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Console\Application;
use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;

class AppServiceProvider extends ServiceProvider
{
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
        // app(BroadcastManager::class)->extend('pusher-custom', function () {
        //     $pusher = new Pusher(
        //         config('broadcasting.connections.pusher.key'),
        //         config('broadcasting.connections.pusher.secret'),
        //         config('broadcasting.connections.pusher.app_id'),
        //         config('broadcasting.connections.pusher.options'),
        //         new \GuzzleHttp\Client(['verify' => false]),
        //     );

        //     return new PusherBroadcaster($pusher);
        // });
    }
}
