<?php

namespace App\Providers;

use App\Services\ClickService;
use Illuminate\Support\ServiceProvider;

class ClickServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\ClickService', function ($app) {
            return new ClickService(
                $app->make('App\Repositories\ClickRepository'),
                $app->make('App\Repositories\BadDomainRepository')
            );
        });
    }
}