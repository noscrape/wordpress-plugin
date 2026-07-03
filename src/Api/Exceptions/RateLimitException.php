<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api\Exceptions;

final class RateLimitException extends ApiException
{
    public function __construct(
        public readonly int $retryAfter = 0,
    ) {
        parent::__construct(429);
    }
}
