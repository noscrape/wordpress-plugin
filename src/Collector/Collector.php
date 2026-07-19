<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Collector;

final class Collector
{
    /**
     * @var array<string, string>
     */
    private array $items = [];

    /**
     * @var array<string, string>
     */
    private array $lookup = [];

    private int $counter = 0;

    public function add(string $text): string
    {
        if (isset($this->lookup[$text])) {
            return "<!-- noscrape:{$this->lookup[$text]} -->";
        }

        $id = 'ns_' . (++$this->counter);

        $this->items[$id] = $text;
        $this->lookup[$text] = $id;

        return "<!-- noscrape:$id -->";
    }

    /**
     * @return array<string, string>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function clear(): void
    {
        $this->items = [];
        $this->lookup = [];
        $this->counter = 0;
    }
}
