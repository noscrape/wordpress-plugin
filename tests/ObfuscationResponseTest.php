<?php

declare(strict_types=1);

use Noscrape\WordPress\Api\Exceptions\InvalidResponseException;
use Noscrape\WordPress\Api\ObfuscationResponse;
use PHPUnit\Framework\TestCase;

final class ObfuscationResponseTest extends TestCase
{
    public function testValidPayloadIsNormalized(): void
    {
        $response = ObfuscationResponse::fromArray(
            [
                'font' => base64_encode('wOF2fontdata'),
                'format' => 'WOFF2',
                'data' => [
                    'ns_1' => 'abc',
                    'ns_2' => 'def',
                    'extra' => 'ignored',
                ],
            ],
            ['ns_1', 'ns_2'],
        );

        self::assertSame(base64_encode('wOF2fontdata'), $response->font);
        self::assertSame('font/woff2', $response->mimeType);
        self::assertSame(
            [
                'ns_1' => 'abc',
                'ns_2' => 'def',
            ],
            $response->items,
        );
    }

    public function testMimeTypePayloadIsAccepted(): void
    {
        $response = ObfuscationResponse::fromArray(
            [
                'font' => base64_encode('wOF2fontdata'),
                'format' => 'application/font-woff2',
                'data' => [
                    'ns_1' => 'abc',
                ],
            ],
            ['ns_1'],
        );

        self::assertSame('font/woff2', $response->mimeType);
    }

    public function testMissingFormatFallsBackToFontDetection(): void
    {
        $response = ObfuscationResponse::fromArray(
            [
                'font' => base64_encode('wOF2fontdata'),
                'data' => [
                    'ns_1' => 'abc',
                ],
            ],
            ['ns_1'],
        );

        self::assertSame('font/woff2', $response->mimeType);
    }

    public function testMissingExpectedItemIsRejected(): void
    {
        $this->expectException(InvalidResponseException::class);

        ObfuscationResponse::fromArray(
            [
                'font' => base64_encode('wOF2fontdata'),
                'format' => 'woff2',
                'data' => [
                    'ns_1' => 'abc',
                ],
            ],
            ['ns_1', 'ns_2'],
        );
    }

    public function testInvalidFormatFallsBackToFontDetection(): void
    {
        $response = ObfuscationResponse::fromArray(
            [
                'font' => base64_encode('wOF2fontdata'),
                'format' => 'woff2;base64,evil',
                'data' => [
                    'ns_1' => 'abc',
                ],
            ],
            ['ns_1'],
        );

        self::assertSame('font/woff2', $response->mimeType);
    }

    public function testUndetectableFontIsRejected(): void
    {
        $this->expectException(InvalidResponseException::class);

        ObfuscationResponse::fromArray(
            [
                'font' => base64_encode('not-a-font'),
                'data' => [
                    'ns_1' => 'abc',
                ],
            ],
            ['ns_1'],
        );
    }
}
