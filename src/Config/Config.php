<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Config;

final class Config
{
    public function apiKey(): ?string
    {
        return $this->stringOption('noscrape_api_key');
    }

    public function host(): ?string
    {
        return $this->stringOption('noscrape_host');
    }

    public function shortcodesEnabled(): bool
    {
        return $this->boolOption(
            'noscrape_shortcodes',
            true,
        );
    }

    public function woocommerceEnabled(): bool
    {
        return $this->boolOption(
            'noscrape_woocommerce',
            true,
        );
    }

    public function woocommerceScreenReaderTextProtectionEnabled(): bool
    {
        return $this->boolOption(
            'noscrape_woocommerce_screen_reader_text',
        );
    }

    private function stringOption(string $key): ?string
    {
        $value = get_option($key);

        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        return trim($value);
    }

    private function boolOption(
        string $key,
        bool $default = false,
    ): bool {
        return (bool) get_option(
            $key,
            $default,
        );
    }
}
