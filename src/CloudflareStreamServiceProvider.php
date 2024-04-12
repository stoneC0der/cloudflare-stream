<?php

namespace StoneC0der\CloudflareStream;

use Illuminate\Support\ServiceProvider;

class CloudflareStreamServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cloudflare.stream', function () {
            return new Stream();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/cloudflare-stream.php' => config_path('cloudflare-stream.php'),
        ]);
    }
}
