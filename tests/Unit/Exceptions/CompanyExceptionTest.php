<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Excptions;

use Andali\Companies\Exceptions\CompanyException;
use Andali\Companies\Tests\TestCase;

/**
 * @covers \Andali\Companies\Exceptions\CompanyException
 */
class CompanyExceptionTest extends TestCase
{
    /** @test */
    public function throws_the_correct_message_for_does_not_belong_to_company():void
    {
        $this->expectExceptionMessage('The user does not belong to the given company.');

        throw CompanyException::doesNotBelongToCompany();
    }

    /** @test */
    public function throws_the_correct_message_for_does_not_have_ownership():void
    {
        $this->expectExceptionMessage('The user does not have ownership on the given company.');

        throw CompanyException::doesNotHaveOwnership();
    }

    /** @test */
    public function throws_the_correct_message_for_email_already_on_company():void
    {
        $this->expectExceptionMessage('The user is already on the company.');

        throw CompanyException::emailAlreadyOnCompany();
    }

    /** @test */
    public function throws_the_correct_message_for_email_already_invited():void
    {
        $this->expectExceptionMessage('The user is already invited to the company.');

        throw CompanyException::emailAlreadyInvited();
    }

    /** @test */
    public function throws_the_correct_message_for_can_not_remove_owner():void
    {
        $this->expectExceptionMessage('The user is holds ownership of the given company.');

        throw CompanyException::canNotRemoveOwner();
    }
}
