<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField\Traits\Support;

use Closure;
use Laravel\Nova\Http\Requests\NovaRequest;

trait InteractsWithCloudinary
{
    public ?string $cloud = null;

    public ?string $username = null;

    public ?string $key = null;

    public ?string $secret = null;

    public ?Closure $configureCallback = null;

    public function cloud(string $cloud): static
    {
        $this->cloud = $cloud;

        return $this;
    }

    public function username(string $username): static
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
        if (!is_callable($this->configureCallback)) {
            return;
        }

        $configuration = call_user_func($this->configureCallback, $request);

        $this->cloud = data_get($configuration, 'cloud');
        $this->username = data_get($configuration, 'username');
        $this->key = data_get($configuration, 'key');
        $this->secret = data_get($configuration, 'secret');
    }

    public function configuration(): array
    {
        $cloud = $this->cloud ?? config('nova-cloudinary.default.cloud');

        return [
            'cloud_name' => $cloud,
            'api_key' => $this->key ?? config('nova-cloudinary.default.key'),
        ];
    }
}
