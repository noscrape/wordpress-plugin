<?php

declare(strict_types=1);

use Noscrape\WordPress\Api\ObfuscationResponse;
use Noscrape\WordPress\Output\Replacer;
use PHPUnit\Framework\TestCase;

if (!function_exists('esc_attr')) {
    function esc_attr(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('esc_html')) {
    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

final class ReplacerTest extends TestCase
{
    public function testEncodedApiResponseIsEscapedBeforeRendering(): void
    {
        $method = new ReflectionMethod(Replacer::class, 'replacePlaceholders');

        $result = $method->invoke(
            new Replacer(),
            '<html><head></head><body><!-- noscrape:ns_1 --></body></html>',
            new ObfuscationResponse(
                base64_encode('wOF2fontdata'),
                'font/woff2',
                ['ns_1' => '<script>alert(1)</script>'],
            ),
        );

        self::assertStringNotContainsString('<script>alert(1)</script>', $result);
        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $result);
    }
}
