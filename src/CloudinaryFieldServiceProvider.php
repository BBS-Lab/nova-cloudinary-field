<?php

namespace BBSLab\CloudinaryField;

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
        if ($this->isNovaInstalled()) {
            $this->publishes([
                __DIR__.'/config/nova-cloudinary.php' => config_path('nova-cloudinary.php'),
            ], 'config');

            \Laravel\Nova\Nova::serving(function (\Laravel\Nova\Events\ServingNova $event) {
                Nova::script('nova-cloudinary-field-external', 'https://media-library.cloudinary.com/global/all.js');
                Nova::script('nova-cloudinary-field', __DIR__.'/../dist/js/field.js');
                Nova::style('nova-cloudinary-field', __DIR__.'/../dist/css/field.css');
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->isNovaInstalled()) {
            $this->mergeConfigFrom(__DIR__.'/config/nova-cloudinary.php', 'nova-cloudinary');
        }
    }

    /**
     * Check if Laravel Nova is installed.
     *
     * @return bool
     */
    protected function isNovaInstalled()
    {
        return class_exists('Laravel\Nova\Nova');
    }
}
