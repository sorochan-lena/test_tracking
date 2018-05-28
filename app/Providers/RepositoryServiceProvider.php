<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Contracts\Repositories\IClickRepository',
            'App\Repositories\ClickRepository'
        );

        $this->app->bind(
            'App\Contracts\Repositories\IBadDomainRepository',
            'App\Repositories\BadDomainRepository'
        );
    }
}