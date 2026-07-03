<?php

declare(strict_types=1);

namespace Noscrape\WordPress;

use Noscrape\WordPress\Admin\SettingsPage;
use Noscrape\WordPress\Config\Config;
use Noscrape\WordPress\Integrations\WooCommerce\WooCommerce;
use Noscrape\WordPress\Output\OutputBuffer;
use Noscrape\WordPress\Output\Replacer;
use Noscrape\WordPress\Shortcodes\NoscrapeShortcode;

final readonly class Plugin
{
    public function __construct(
        private Config $config = new Config(),
    )
    {
    }

    public function boot(): void
    {
        (new SettingsPage($this->config,))->boot();

        (new OutputBuffer(fn(string $html) => $this->render($html),))->boot();

        if ($this->config->shortcodesEnabled()) {
            (new NoscrapeShortcode())->boot();
        }

        if ($this->config->woocommerceEnabled()) {
            (new WooCommerce())->boot();
        }
    }

    private function render(string $html): string
    {
        return (new Replacer())->replace($html);
    }
}
