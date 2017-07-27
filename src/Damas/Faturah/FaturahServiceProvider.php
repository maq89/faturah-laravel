<?php

namespace Damas\Faturah;

use Illuminate\Support\ServiceProvider;

class FaturahServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //include __DIR__.'/routes.php';
        //$this->app->make('Damas\Faturah\FaturahController');
	    $this->app->singleton('Faturah', function() {
			return new Faturah;
	    });
    }
}
