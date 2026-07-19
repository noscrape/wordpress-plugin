<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Output;

use Noscrape\WordPress\Support\Container;

final class OutputBuffer
{
    /**
     * @var callable(string): string
     */
    private $callback;

    private bool $started = false;

    /**
     * @param callable(string): string $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function boot(): void
    {
        add_action('template_redirect', [$this, 'start'], 0);
    }

    public function start(): void
    {
        if (
            $this->started
            ||
            is_admin()
            || wp_doing_ajax()
            || (defined('REST_REQUEST') && REST_REQUEST)
            || (function_exists('wp_is_json_request') && wp_is_json_request())
            || is_feed()
            || is_embed()
            || is_robots()
            || is_trackback()
        ) {
            return;
        }

        Container::collector()->clear();
        ob_start($this->callback);
        $this->started = true;
    }
}
