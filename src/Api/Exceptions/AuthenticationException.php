<?php

declare(strict_types=1);

namespace Noscrape\WordPress\Api\Exceptions;

final class AuthenticationException extends ApiException
{
    public function __construct()
    {
        parent::__construct(401);
    }
}
