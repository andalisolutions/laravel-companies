<?php

declare(strict_types=1);

namespace Andali\Companies\Exceptions;

use Exception;

class CompanyException extends Exception
{
    public static function doesNotBelongToCompany(): self
    {
        return new static('The user does not belong to the given company.');
    }

    public static function doesNotHaveOwnership(): self
    {
        return new static('The user does not have ownership on the given company.');
    }

    public static function emailAlreadyOnCompany(): self
    {
        return new static('The user is already on the company.');
    }

    public static function emailAlreadyInvited(): self
    {
        return new static('The user is already invited to the company.');
    }

    public static function canNotRemoveOwner(): self
    {
        return new static('The user is holds ownership of the given company.');
    }
}
