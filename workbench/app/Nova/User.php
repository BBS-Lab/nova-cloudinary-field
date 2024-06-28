<?php

declare(strict_types=1);

namespace Workbench\App\Nova;

use BBSLab\CloudinaryField\Cloudinary;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    public static $model = \Workbench\App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'email',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Cloudinary::make('Image')
                ->multiple()
                ->nullable(),

            Cloudinary::make('Flat image')
                ->resolveUsing(function ($value, $resource, $attribute) {
                    if (empty($value)) {
                        return [];
                    }

                    $client = new \Cloudinary\Cloudinary([
                        'cloud_name' => config('nova-cloudinary.default.cloud'),
                        'api_key' => config('nova-cloudinary.default.key'),
                        'api_secret' => config('nova-cloudinary.default.secret'),
                    ]);

                    return [
                        [
                            'public_id' => $value,
                            'secure_url' => $client->image($value)->toUrl(),
                            'resource_type' => 'image',
                            'type' => 'upload',
                            'format' => null,
                            'bytes' => 0,
                        ],
                    ];
                })
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                    tap($request[$requestAttribute], function ($value) use ($model, $attribute) {
                        if (!is_array($value)) {
                            $value = json_decode($value, true, JSON_THROW_ON_ERROR);
                        }

                        $model->{$attribute} = data_get($value, '0.public_id');
                    });
                })
                ->nullable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),
        ];
    }
}
