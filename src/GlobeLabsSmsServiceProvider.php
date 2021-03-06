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
                    'verify' => config('broadcasting.connections.globe_labs_sms.verify_ssl', true),
                ]);
            });

        $this->loadLang();

        $this->publishLang();
    }

    private function loadLang()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'globe_labs_sms');
    }

    private function publishLang()
    {
        // Publish languages for override
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang/vendor/globe_labs_sms'),
        ], 'locales');
    }
}
