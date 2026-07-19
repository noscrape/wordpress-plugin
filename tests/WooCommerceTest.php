<?php

declare(strict_types=1);

use Noscrape\WordPress\Integrations\WooCommerce\WooCommerce;
use Noscrape\WordPress\Support\Container;
use PHPUnit\Framework\TestCase;

if (!function_exists('get_option')) {
    function get_option(string $key, mixed $default = false): mixed
    {
        return $GLOBALS['noscrape_test_options'][$key] ?? $default;
    }
}

if (!function_exists('get_bloginfo')) {
    function get_bloginfo(string $show = ''): string
    {
        return 'UTF-8';
    }
}

if (!function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags(string $string): string
    {
        return strip_tags($string);
    }
}

final class WooCommerceTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['noscrape_test_options'] = [];
        Container::reset();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['noscrape_test_options']);
        Container::reset();
    }

    public function testScreenReaderPriceTextRemainsReadableByDefault(): void
    {
        $html = $this->priceHtml();

        $result = (new WooCommerce())->cartPriceHtml($html);

        self::assertStringContainsString('<!-- noscrape:ns_1 -->', $result);
        self::assertStringContainsString('Aktueller Preis ist: 9,00&nbsp;€.', $result);
        self::assertSame(
            ['ns_1' => "9,00\xc2\xa0€"],
            Container::collector()->items(),
        );
    }

    public function testScreenReaderPriceTextIsProtectedWhenEnabled(): void
    {
        $GLOBALS['noscrape_test_options']['noscrape_woocommerce_screen_reader_text'] = true;
        $html = $this->priceHtml();

        $result = (new WooCommerce())->cartPriceHtml($html);

        self::assertStringNotContainsString('Aktueller Preis ist: 9,00&nbsp;€.', $result);
        self::assertSame(
            [
                'ns_1' => "9,00\xc2\xa0€",
                'ns_2' => "Aktueller Preis ist: 9,00\xc2\xa0€.",
            ],
            Container::collector()->items(),
        );
    }

    private function priceHtml(): string
    {
        return '<span class="woocommerce-Price-amount amount"><bdi>9,00&nbsp;€</bdi></span><span class="screen-reader-text">Aktueller Preis ist: 9,00&nbsp;€.</span>';
    }
}
