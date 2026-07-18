<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Integrations\WooCommerce;

use Noscrape\WordPress\Support\Container;
use WC_Product;

final readonly class WooCommerce
{
    public function boot(): void
    {
        add_action('plugins_loaded', [$this, 'registerHooks'], 20);
    }

    public function registerHooks(): void
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        add_filter('woocommerce_get_price_html', [$this, 'priceHtml'], 999, 2);
        add_filter('woocommerce_variable_price_html', [$this, 'priceHtml'], 999, 2);
        add_filter('woocommerce_variable_sale_price_html', [$this, 'priceHtml'], 999, 2);
        add_filter('woocommerce_grouped_price_html', [$this, 'priceHtml'], 999, 2);

        add_filter('woocommerce_cart_item_price', [$this, 'cartPriceHtml'], 999, 3);
        add_filter('woocommerce_cart_item_subtotal', [$this, 'cartSubtotalHtml'], 999, 3);
    }

    public function priceHtml(string $html, WC_Product $product): string
    {
        return $this->protectPriceHtml($html);
    }

    public function cartPriceHtml(string $html): string
    {
        return $this->protectPriceHtml($html);
    }

    public function cartSubtotalHtml(string $html): string
    {
        return $this->protectPriceHtml($html);
    }

    private function protectPriceHtml(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        return preg_replace_callback(
            '/<bdi\b[^>]*>(.*?)<\/bdi>/is',
            static function (array $matches): string {
                $text = trim(
                    html_entity_decode(
                        wp_strip_all_tags($matches[1]),
                        ENT_QUOTES | ENT_HTML5,
                        get_bloginfo('charset') ?: 'UTF-8',
                    ),
                );

                if ($text === '') {
                    return $matches[0];
                }

                return sprintf(
                    '<bdi>%s</bdi>',
                    Container::collector()->add($text),
                );
            },
            $html,
        ) ?? $html;
    }
}
