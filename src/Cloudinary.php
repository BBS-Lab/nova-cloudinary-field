<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField;

use BBSLab\CloudinaryField\Contracts\Support\InteractsWithCloudinary as InteractsWithCloudinaryContract;
use BBSLab\CloudinaryField\Traits\Support\InteractsWithCloudinary;
use JsonException;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cloudinary extends Field implements InteractsWithCloudinaryContract
{
    use InteractsWithCloudinary {
        InteractsWithCloudinary::configuration as baseConfiguration;
    }

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-cloudinary-field';

    public bool $multiple = false;

    public ?int $limit = null;

    public function multiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function limit(?int $limit = null): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function configuration(): array
    {
        return array_merge($this->baseConfiguration(), [
            'multiple' => $this->multiple,
            'max_files' => $this->limit,
        ]);
    }

    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $value = $request->input($requestAttribute);

        try {
            $payload = json_decode($value ?? '', true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $payload = [];
        }

        $model->{$attribute} = $payload;
    }

    public function jsonSerialize(): array
    {
        return with(app(NovaRequest::class), function ($request) {
            $this->resolveConfiguration($request);

            $configuration = $this->configuration();

            return array_merge(parent::jsonSerialize(), [
                'configuration' => $configuration,
                'widgetKey' => md5(json_encode($configuration)),
            ]);
        });
    }
}
