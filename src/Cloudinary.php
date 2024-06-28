<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField;

use BBSLab\CloudinaryField\Contracts\Support\InteractsWithCloudinary as InteractsWithCloudinaryContract;
use BBSLab\CloudinaryField\Traits\Support\InteractsWithCloudinary;
use Closure;
use JsonException;
use Laravel\Nova\Contracts\Cover;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\PresentsImages;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cloudinary extends Field implements Cover, InteractsWithCloudinaryContract
{
    use InteractsWithCloudinary {
        InteractsWithCloudinary::configuration as baseConfiguration;
    }
    use PresentsImages;
    use SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-cloudinary-field';

    public bool $multiple = false;

    public ?int $limit = null;

    public ?Closure $resolvePublicIdCallback = null;

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

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
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

    public function client(): \Cloudinary\Cloudinary
    {
        return new \Cloudinary\Cloudinary([
            'cloud_name' => $this->cloud ?? config('nova-cloudinary.default.cloud'),
            'api_key' => $this->key ?? config('nova-cloudinary.default.key'),
            'api_secret' => $this->secret ?? config('nova-cloudinary.default.secret'),
        ]);
    }

    public function resolvePublicIdUsing(Closure $resolvePublicIdCallback): static
    {
        $this->resolvePublicIdCallback = $resolvePublicIdCallback;

        return $this;
    }

    protected function resolvePublicId(): ?string
    {
        if ($this->resolvePublicIdCallback) {
            return call_user_func($this->resolvePublicIdCallback);
        }

        return data_get($this->value, '0.public_id');
    }

    public function resolveThumbnailUrl(): ?string
    {
        if (empty($this->value)) {
            return null;
        }

        return (string) $this->client()->image($this->resolvePublicId())->toUrl();
    }
}
