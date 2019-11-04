<?php

namespace BBSLab\CloudinaryField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class CloudinaryFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/nova-cloudinary.php' => config_path('nova-cloudinary.php'),
        ], 'config');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-cloudinary-field-external', 'https://media-library.cloudinary.com/global/all.js');
            Nova::script('nova-cloudinary-field', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-cloudinary-field', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/nova-cloudinary.php', 'nova-cloudinary'
        );
    }
}
