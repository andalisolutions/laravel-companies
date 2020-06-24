<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Actions;

use Andali\Companies\Actions\RemoveCompanyMemberAction;
use Andali\Companies\Tests\TestCase;

/**
 * @covers \Andali\Companies\Actions\RemoveCompanyMemberAction
 */
class RemoveCompanyMemberActionTest extends TestCase
{
    /** @test */
    public function throws_when_the_owner_is_attempted_to_be_removed(): void
    {
        $this->expectExceptionMessage('The user is holds ownership of the given company.');

        $company = $this->company();

        $this->actingAs($company->owner);

        (new RemoveCompanyMemberAction($company))->execute($company->owner);
    }

    /** @test */
    public function can_remove_the_given_member(): void
    {
        $user    = $this->user();
        $company = $this->company();

        $company->addMember($user, 'member', []);

        $this->actingAs($company->owner);

        $this->assertTrue($user->onCompany($company));

        (new RemoveCompanyMemberAction($company))->execute($user);

        $this->assertFalse($user->fresh()->onCompany($company));
    }
}
