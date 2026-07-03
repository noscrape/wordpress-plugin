<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Support;

use Noscrape\WordPress\Api\Client;
use Noscrape\WordPress\Collector\Collector;
use Noscrape\WordPress\Config\Config;

final class Container
{
    private static ?Collector $collector = null;
    private static ?Config $config = null;
    private static ?Client $client = null;

    public static function collector(): Collector
    {
        return self::$collector ??= new Collector();
    }

    public static function config(): Config
    {
        return self::$config ??= new Config();
    }

    public static function client(): Client
    {
        return self::$client ??= new Client(
            self::config(),
        );
    }

    public static function reset(): void
    {
        self::$collector = null;
        self::$config = null;
        self::$client = null;
    }
}
