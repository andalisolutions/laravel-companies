<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Actions;

use Andali\Companies\Actions\AcceptCompanyInvitationAction;
use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Tests\TestCase;

/**
 * @covers \Andali\Companies\Actions\AcceptCompanyInvitationAction
 */
class AcceptCompanyInvitationActionTest extends TestCase
{
    /** @test */
    public function throws_when_a_different_user_attempts_to_accept_an_invitation(): void
    {
        $this->expectExceptionMessage('The user is not authorized to claim the given invitation.');

        $user          = $this->user();
        $company       = $this->company();
        $invitation    = factory(CompanyInvitation::class)->create([
            'company_id' => $company->id,
            'user_id'    => $user->id,
        ]);

        $this->actingAs($company->owner);

        (new AcceptCompanyInvitationAction($invitation))->execute();
    }

    /** @test */
    public function can_accept_the_company_invitation(): void
    {
        $user          = $this->user();
        $company       = $this->company();
        $invitation    = factory(CompanyInvitation::class)->create([
            'company_id' => $company->id,
            'user_id'    => $user->id,
        ]);

        $this->actingAs($user);

        $this->assertDatabaseHas('company_invitations', ['id'=> $invitation->id]);
        $this->assertFalse($user->onCompany($company));

        (new AcceptCompanyInvitationAction($invitation))->execute();

        $this->assertDatabaseMissing('company_invitations', ['id'=> $invitation->id]);
        $this->assertTrue($user->fresh()->onCompany($company));
    }
}
