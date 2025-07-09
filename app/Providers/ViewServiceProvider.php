<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $view->with('getRolesString', function ($masterSurat) {
                return (new Controller)->getRolesString($masterSurat);
            });
        });
    }
}
