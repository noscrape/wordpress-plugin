<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api\Exceptions;

use RuntimeException;

final class InvalidResponseException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'Invalid API response.',
        );
    }
}
