<?php

declare(strict_types=1);

namespace Andali\Companies\Exceptions;

use Exception;

class CompanyInvitationException extends Exception
{
    public static function expirationExceeded(): self
    {
        return new static('The invitation has exceeded the expiration date.');
    }

    public static function attemptedClaimByUnauthorizedUser(): self
    {
        return new static('The user is not authorized to claim the given invitation.');
    }
}
