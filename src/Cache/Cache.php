<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Cache;

final class Cache
{
    private const PREFIX = 'noscrape_';

    private const DEFAULT_TTL = 60 * 60 * 24 * 7;
    public function get(string $text): ?string
    {
        $value = get_transient(
            self::PREFIX . md5($text),
        );

        return is_string($value)
            ? $value
            : null;
    }

    public function put(
        string $text,
        string $encoded,
        int $ttl = self::DEFAULT_TTL,
    ): void {
        set_transient(
            self::PREFIX . md5($text),
            $encoded,
            $ttl,
        );
    }
}
