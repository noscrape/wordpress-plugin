<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api;

use Noscrape\WordPress\Api\Exceptions\ApiException;
use Noscrape\WordPress\Api\Exceptions\AuthenticationException;
use Noscrape\WordPress\Api\Exceptions\ConnectionException;
use Noscrape\WordPress\Api\Exceptions\InvalidResponseException;
use Noscrape\WordPress\Api\Exceptions\RateLimitException;
use Noscrape\WordPress\Config\Config;

final readonly class Client
{
    public function __construct(
        private Config $config,
    )
    {
    }

    public function obfuscate(array $items, ?string $font = null): ObfuscationResponse
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

        if ($response instanceof \WP_Error) {
            throw new ConnectionException($response->get_error_message(),);
        }

        $status = wp_remote_retrieve_response_code($response);

        match ($status) {
            200 => null,
            401, 403 => throw new AuthenticationException(),
            429 => throw new RateLimitException(
                (int)wp_remote_retrieve_header(
                    $response,
                    'Retry-After',
                ),
            ),
            default => $status >= 400
                // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- False positive. WP_Error message is not output.
                ? throw new ApiException($status)
                : null,
        };

        /** @var array|null $json */
        $json = json_decode(
            wp_remote_retrieve_body($response),
            true,
        );

        if (!is_array($json)) {
            throw new InvalidResponseException();
        }

        return ObfuscationResponse::fromArray(
            $json,
            array_keys($items),
        );
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
