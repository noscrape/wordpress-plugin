<?php

declare(strict_types=1);

use Noscrape\WordPress\Collector\Collector;
use PHPUnit\Framework\TestCase;

final class CollectorTest extends TestCase
{
    public function testDuplicateTextReusesPlaceholder(): void
    {
        $collector = new Collector();

        $first = $collector->add('hello@example.com');
        $second = $collector->add('hello@example.com');

        self::assertSame('<!-- noscrape:ns_1 -->', $first);
        self::assertSame($first, $second);
        self::assertSame(
            ['ns_1' => 'hello@example.com'],
            $collector->items(),
        );
    }

    public function testClearResetsLookupAndCounter(): void
    {
        $collector = new Collector();

        $collector->add('hello@example.com');
        $collector->clear();

        self::assertSame([], $collector->items());
        self::assertSame(
            '<!-- noscrape:ns_1 -->',
            $collector->add('hello@example.com'),
        );
    }
}
