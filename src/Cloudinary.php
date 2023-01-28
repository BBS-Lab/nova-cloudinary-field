<?php

namespace BBSLab\CloudinaryField;

use Closure;
use JsonException;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cloudinary extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-cloudinary-field';

    public ?string $cloud = null;
    public ?string $username = null;
    public ?string $key = null;
    public ?string $secret = null;
    public ?Closure $configureCallback = null;

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

    public function cloud(string $cloud): static
    {
        $this->cloud = $cloud;

        return $this;
    }

    public function user(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function secret(string $secret): static
    {
        $this->secret = $secret;

        return $this;
    }

    public function configureUsing(Closure $callback): static
    {
        $this->configureCallback = $callback;

        return $this;
    }

    protected function resolveConfiguration(NovaRequest $request): void
    {
        if (!$this->configureCallback || !is_callable($this->configureCallback)) {
            return;
        }

        $configuration = call_user_func($this->configureCallback, $request);

        $this->cloud = data_get($configuration, 'cloud');
        $this->username = data_get($configuration, 'username');
        $this->key = data_get($configuration, 'key');
        $this->secret = data_get($configuration, 'secret');
    }

    public function signature(): array
    {
        $cloud = $this->cloud ?? config('nova-cloudinary.default.cloud');
        $username = $this->username ?? config('nova-cloudinary.default.username');
        $secret = $this->secret ?? config('nova-cloudinary.default.secret');
        $timestamp = now()->timestamp;

        return [
            'cloud_name' => $cloud,
            'api_key' => $this->key ?? config('nova-cloudinary.default.key'),
            'username' => $username,
            'timestamp' => $timestamp,
            'signature' => hash(
                algo: 'sha256',
                data: "cloud_name={$cloud}&timestamp={$timestamp}&username={$username}{$secret}",
            ),
            'multiple' => $this->multiple,
            'max_files' => $this->limit,
        ];
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

            $configuration = $this->signature();

            return array_merge(parent::jsonSerialize(), [
                'configuration' => $this->signature(),
                'widgetKey' => md5(json_encode($configuration)),
            ]);
        });
    }
}
