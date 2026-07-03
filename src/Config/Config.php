<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Config;

final readonly class Config
{
    public function apiKey(): ?string
    {
        return $this->get('api_key');
    }

    public function host(): ?string
    {
        return $this->get('host');
    }

    public function cacheEnabled(): bool
    {
        return (bool) $this->get('cache', true);
    }

    private function get(string $key, mixed $default = null): mixed
    {
        return get_option(
            "noscrape_{$key}",
            $default,
        );
    }
}
