<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api;

use Noscrape\WordPress\Config\Config;
use RuntimeException;

final readonly class Client
{
    public function __construct(
        private Config $config,
    ) {
    }

    public function obfuscate(array $items, ?string $font = null): array
    {
        $body = [
            'items' => $items,
            'ignoreWhitespace' => true,
        ];

        if ($font !== null) {
            $body['font'] = $font;
        }

        $response = wp_remote_post(
            $this->endpoint(),
            [
                'timeout' => 15,
                'headers' => $this->headers(),
                'body' => wp_json_encode($body),
            ],
        );

        if (is_wp_error($response)) {
            throw new RuntimeException($response->get_error_message());
        }

        $status = wp_remote_retrieve_response_code($response);

        if ($status >= 400) {
            throw new RuntimeException(
                sprintf(
                    'Noscrape API returned HTTP %d.',
                    $status,
                ),
            );
        }

        /** @var array|null $json */
        $json = json_decode(
            wp_remote_retrieve_body($response),
            true,
        );

        if (!is_array($json)) {
            throw new RuntimeException(
                'Invalid API response.',
            );
        }

        return $json;
    }

    private function endpoint(): string
    {
        $host = rtrim(
            $this->config->host() ?: 'https://api.noscrape.eu',
            '/',
        );

        return "{$host}/obfuscate";
    }

    private function headers(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($this->config->apiKey()) {
            $headers['Authorization'] = sprintf(
                'Bearer %s',
                $this->config->apiKey(),
            );
        }

        return $headers;
    }
}
