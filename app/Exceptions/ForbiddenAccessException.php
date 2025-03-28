<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ForbiddenAccessException extends Exception
{
    /**
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? __('client.Access Forbidden'), ResponseAlias::HTTP_FORBIDDEN);
    }
}
