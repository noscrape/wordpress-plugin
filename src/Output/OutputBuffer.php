<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Output;

final class OutputBuffer
{
    /**
     * @var callable(string): string
     */
    private $callback;

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
        if (is_admin()) {
            return;
        }

        ob_start($this->callback);
    }
}
