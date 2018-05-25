<?php

namespace Coreproc\GlobeLabsSms;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class GlobeLabsSmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(GlobeLabsSmsChannel::class)
            ->needs(Client::class)
            ->give(function () {
                return new Client([
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'verify' => false
                ]);
            });
    }
}
