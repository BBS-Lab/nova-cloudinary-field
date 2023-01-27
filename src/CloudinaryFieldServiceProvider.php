<?php

namespace BBSLab\CloudinaryField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CloudinaryFieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->config();
        $this->translations();

        Nova::serving(function (ServingNova $event) {
            $translations = trans('nova-cloudinary::ui');

            if (!is_array($translations)) {
                $translations = [];
            }

            $translations = array_merge(
                trans('nova-cloudinary::ui', [], 'en'),
                $translations
            );

            Nova::translations($translations);


            Nova::script('cloudinary-scripts', 'https://media-library.cloudinary.com/global/all.js');
            Nova::script('nova-cloudinary-field', __DIR__.'/../dist/js/tool.js');
            Nova::style('nova-cloudinary-field', __DIR__.'/../dist/css/tool.css');
        });
    }

    public function register(): void
    {
        //
    }

    public function config(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-cloudinary.php', 'nova-cloudinary');

        $this->publishes(
            [
                __DIR__.'/../config' => config_path(),
            ],
            'nova-cloudinary-config'
        );
    }

    protected function translations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'nova-cloudinary');
        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');
    }
}
