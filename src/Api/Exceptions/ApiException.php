<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api\Exceptions;

use RuntimeException;

class ApiException extends RuntimeException
{
    public function __construct(
        public readonly int $status,
    ) {
        parent::__construct(
            sprintf(
                'Noscrape API returned HTTP %d.',
                $status,
            ),
        );
    }
}
