<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField;

use BBSLab\CloudinaryField\Http\Middleware\Authorize;
use Cloudinary\Cloudinary;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudinaryFieldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {

        $package
            ->name('nova-cloudinary')
            ->hasConfigFile(['nova-cloudinary'])
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand->publishConfigFile();
            });
    }

    public function packageBooted(): void
    {
        $this->routes();

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

    public function packageRegistered(): void
    {
        $this->app->singleton('default_cloudinary', function () {
            return new Cloudinary([
                'cloud_name' => config('nova-cloudinary.default.cloud'),
                'api_key' => config('nova-cloudinary.default.key'),
                'api_secret' => config('nova-cloudinary.default.secret'),
            ]);
        });
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authenticate::class, Authorize::class], 'nova-cloudinary')
            ->group(__DIR__.'/../routes/inertia.php');
    }
}
