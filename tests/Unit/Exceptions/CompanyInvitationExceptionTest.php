<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Excptions;

use Andali\Companies\Exceptions\CompanyInvitationException;
use Andali\Companies\Tests\TestCase;

/**
 * @covers \Andali\Companies\Exceptions\CompanyInvitationException
 */
class CompanyInvitationExceptionTest extends TestCase
{
    /** @test */
    public function throws_the_correct_message_for_expiration_exceeded():void
    {
        $this->expectExceptionMessage('The invitation has exceeded the expiration date.');

        throw CompanyInvitationException::expirationExceeded();
    }

    /** @test */
    public function throws_the_correct_message_for_attempted_claim_by_unauthorized_user():void
    {
        $this->expectExceptionMessage('The user is not authorized to claim the given invitation.');

        throw CompanyInvitationException::attemptedClaimByUnauthorizedUser();
    }
}
