<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api;

use Noscrape\WordPress\Api\Exceptions\InvalidResponseException;

final readonly class ObfuscationResponse
{
    /**
     * @param array<string, string> $items
     */
    public function __construct(
        public string $font,
        public string $mimeType,
        public array $items,
    ) {
    }

    /**
     * @param array<mixed> $payload
     * @param array<int, string> $expectedIds
     */
    public static function fromArray(array $payload, array $expectedIds): self
    {
        $font = $payload['font'] ?? null;
        $data = $payload['data'] ?? null;

        if (!is_string($font) || $font === '') {
            throw new InvalidResponseException();
        }

        if (!is_array($data)) {
            throw new InvalidResponseException();
        }

        $items = $data['items'] ?? $data;

        if (!is_array($items)) {
            throw new InvalidResponseException();
        }

        $normalizedItems = [];

        foreach ($expectedIds as $id) {
            $value = $items[$id] ?? null;

            if (!is_string($value)) {
                throw new InvalidResponseException();
            }

            $normalizedItems[$id] = $value;
        }

        return new self(
            $font,
            self::normalizeMimeType(
                $payload['format'] ?? null,
                $font,
            ),
            $normalizedItems,
        );
    }

    private static function normalizeMimeType(mixed $format, string $font): string
    {
        if (is_string($format)) {
            $format = strtolower(trim($format));

            if (
                $format !== ''
                && !str_contains($format, ';')
                && !str_contains($format, ',')
                && preg_match('/\s/', $format) !== 1
            ) {
                return match ($format) {
                    'woff', 'woff2', 'ttf', 'otf' => "font/{$format}",
                    'font/woff', 'font/woff2', 'font/ttf', 'font/otf' => $format,
                    'application/font-woff' => 'font/woff',
                    'application/font-woff2' => 'font/woff2',
                    'application/x-font-ttf' => 'font/ttf',
                    'application/x-font-opentype' => 'font/otf',
                    default => self::detectMimeTypeFromFont($font),
                };
            }
        }

        return self::detectMimeTypeFromFont($font);
    }

    private static function detectMimeTypeFromFont(string $font): string
    {
        $binary = base64_decode($font, true);

        if ($binary === false || $binary === '') {
            throw new InvalidResponseException();
        }

        if (str_starts_with($binary, 'wOFF')) {
            return 'font/woff';
        }

        if (str_starts_with($binary, 'wOF2')) {
            return 'font/woff2';
        }

        if (str_starts_with($binary, 'OTTO')) {
            return 'font/otf';
        }

        if (substr($binary, 0, 4) === "\x00\x01\x00\x00") {
            return 'font/ttf';
        }

        throw new InvalidResponseException();
    }
}
