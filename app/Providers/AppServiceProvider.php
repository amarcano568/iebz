<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Tickets;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Tickets::observe(\App\Observers\nuevosTickets::class);
        Schema::defaultStringLength(120);
    }
}
