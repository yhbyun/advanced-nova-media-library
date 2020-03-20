<?php

namespace Ebess\AdvancedNovaMediaLibrary;

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class AdvancedNovaMediaLibraryServiceProvider extends ServiceProvider
{
    protected const LANGUAGE_FOLDER = __DIR__ . '/../resources/lang';

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/nova-media-library.php' => config_path('nova-media-library.php'),
        ], 'nova-media-library');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('media-lib-images-field', __DIR__ . '/../dist/js/field.js');
            Nova::translations(self::LANGUAGE_FOLDER . '/' . app()->getLocale() . '/lang.json');
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/ebess/advanced-nova-media-library')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
