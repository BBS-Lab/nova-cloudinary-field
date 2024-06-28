<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField\Contracts\Support;

use Closure;

interface InteractsWithCloudinary
{
    public function cloud(string $cloud): static;

    public function username(string $username): static;

    public function key(string $key): static;

    public function secret(string $secret): static;

    public function configureUsing(Closure $callback): static;

    public function configuration(): array;
}
