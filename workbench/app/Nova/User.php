<?php

declare(strict_types=1);

namespace Workbench\App\Nova;

use BBSLab\CloudinaryField\Cloudinary;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Workbench\App\Models\User>
     */
    public static $model = \Workbench\App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

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
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

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
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
