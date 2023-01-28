<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField;

use BBSLab\CloudinaryField\Contracts\Support\InteractsWithCloudinary as InteractsWithCloudinaryContract;
use BBSLab\CloudinaryField\Traits\Support\InteractsWithCloudinary;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;

class NovaCloudinary extends Tool implements InteractsWithCloudinaryContract
{
    use InteractsWithCloudinary;

    public function menu(Request $request): mixed
    {
        return MenuSection::make('Cloudinary media library')
            ->path('/nova-cloudinary')
            ->icon('cloud');
    }
}
