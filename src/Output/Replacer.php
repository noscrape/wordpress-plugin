<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Output;

use Noscrape\WordPress\Api\Client;
use Noscrape\WordPress\Config\Config;
use Noscrape\WordPress\Support\Container;

final readonly class Replacer
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            new Config(),
        );
    }

    public function replace(string $html): string
    {
        $collector = Container::collector();

        if ($collector->isEmpty()) {
            return $html;
        }

        $response = $this->client->obfuscate(
            $collector->items(),
        );

        $family = 'noscrape';

        foreach ($response['data']['items'] as $id => $encoded) {
            $replacement = sprintf(
                '<span class="noscrape" style="font-family:%s">%s</span>',
                esc_attr($family),
                $encoded,
            );

            $html = str_replace(
                "<!-- noscrape:{$id} -->",
                $replacement,
                $html,
            );
        }

        $style = sprintf(
            '<style>
                @font-face{
                    font-family:%1$s;
                    src:url(data:font/%2$s;base64,%3$s);
                    font-display:block;
                }

                .noscrape{
                    font-family:%1$s;
                    font-style:normal;
                    font-weight:400;
                    white-space:pre-wrap;
                }
            </style>',
            $family,
            $response['format'],
            $response['font'],
        );

        if (str_contains($html, '</head>')) {
            return str_replace(
                '</head>',
                $style . '</head>',
                $html,
            );
        }

        return $style . $html;
    }
}
