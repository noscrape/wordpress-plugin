<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Support;

use Noscrape\WordPress\Collector\Collector;
use Noscrape\WordPress\Output\Replacer;

final class Container
{
    private static ?Collector $collector = null;

    public static function collector(): Collector
    {
        return self::$collector ??= new Collector();
    }

    public static function reset(): void
    {
        self::$collector = null;
    }
}
