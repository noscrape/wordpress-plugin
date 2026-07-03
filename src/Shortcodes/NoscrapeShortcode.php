<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Shortcodes;

use Noscrape\WordPress\Support\Container;

final readonly class NoscrapeShortcode
{
    public function boot(): void
    {
        add_shortcode(
            'noscrape',
            [$this, 'render'],
        );
    }

    public function render(array $attributes, ?string $content): string
    {
        if ($content === null) {
            return '';
        }

        return Container::collector()->add(trim($content));
    }
}
