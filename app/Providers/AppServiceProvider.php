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
    public function boot(BroadcastManager $broadcastManager): void
    {
        //Create custom pusher, because they don't allow us to modify Guzzle client via config broadcasting.php, make sure you set pusher-custom in .env
        $broadcastManager->extend('pusher-custom', function (Application $app, array $config) {
            $client = new \GuzzleHttp\Client(['verify' => false]);

            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true,
                'host' => 'social.progmix.dev',
                'port' => env('PUSHER_PORT', 6001),
                'useTLS' => env('PUSHER_SCHEME') == 'https',
                'scheme' => env('PUSHER_SCHEME', 'https'),
            ];

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options,
                $client //set custom guzzle client
            );

            return new PusherBroadcaster($pusher);
        });
    }
}
