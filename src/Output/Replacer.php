<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Output;

use Noscrape\WordPress\Api\Exceptions\ApiException;
use Noscrape\WordPress\Api\Exceptions\AuthenticationException;
use Noscrape\WordPress\Api\Exceptions\ConnectionException;
use Noscrape\WordPress\Api\Exceptions\InvalidResponseException;
use Noscrape\WordPress\Api\Exceptions\RateLimitException;
use Noscrape\WordPress\Api\ObfuscationResponse;
use Noscrape\WordPress\Collector\Collector;
use Noscrape\WordPress\Support\Container;

final readonly class Replacer
{
    public function replace(string $html): string
    {
        $collector = Container::collector();

        if ($collector->isEmpty()) {
            return $html;
        }

        try {
            $response = Container::client()->obfuscate($collector->items());
        } catch (RateLimitException $e) {
            $this->storeNotice(
                'warning',
                __('The Noscrape API rate limit has been reached. Your website is temporarily being served without obfuscation.', 'noscrape'),
            );

            return $this->restoreOriginalContent($collector, $html);
        } catch (AuthenticationException $e) {
            $this->storeNotice(
                'error',
                __('Authentication with the Noscrape API failed. Please verify your API key.', 'noscrape'),
            );

            return $this->restoreOriginalContent($collector, $html);
        } catch (ConnectionException $e) {
            $this->storeNotice(
                'error',
                __('The Noscrape API could not be reached. Please check your internet connection or try again later.', 'noscrape'),
            );

            return $this->restoreOriginalContent($collector, $html);
        } catch (InvalidResponseException $e) {
            $this->storeNotice(
                'error',
                __('The Noscrape API returned an invalid response.', 'noscrape'),
            );

            return $this->restoreOriginalContent($collector, $html);
        } catch (ApiException $e) {
            $this->storeNotice(
                'error',
                sprintf(
                    /* translators: %d: HTTP status code returned by the Noscrape API. */
                    __('The Noscrape API returned HTTP %d.', 'noscrape'),
                    $e->status,
                ),
            );

            return $this->restoreOriginalContent($collector, $html);
        } finally {
            $collector->clear();
        }

        return $this->replacePlaceholders($html, $response);
    }

    private function replacePlaceholders(string $html, ObfuscationResponse $response): string
    {
        $family = 'noscrape-' . substr(
            sha1($response->font),
            0,
            8,
        );

        foreach ($response->items as $id => $encoded) {
            $replacement = sprintf(
                '<span class="noscrape" style="font-family:%s">%s</span>',
                esc_attr($family),
                esc_html($encoded),
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
    src:url(data:%2$s;base64,%3$s);
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
            $response->mimeType,
            $response->font,
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

    private function restoreOriginalContent(Collector $collector, string $html): string
    {
        foreach ($collector->items() as $id => $text) {
            $html = str_replace(
                "<!-- noscrape:{$id} -->",
                esc_html($text),
                $html,
            );
        }

        return $html;
    }

    private function storeNotice(string $type, string $message): void
    {
        set_transient(
            'noscrape_admin_notice',
            [
                'type' => $type,
                'time' => time(),
                'message' => $message,
            ],
            300,
        );
    }
}
