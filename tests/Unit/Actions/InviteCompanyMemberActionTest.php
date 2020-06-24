<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Actions;

use Andali\Companies\Actions\InviteCompanyMemberAction;
use Andali\Companies\Mail\InviteExistingUser;
use Andali\Companies\Mail\InviteNewUser;
use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Mail;

/**
 * @covers \Andali\Companies\Actions\InviteCompanyMemberAction
 */
class InviteCompanyMemberActionTest extends TestCase
{
    /** @test */
    public function throws_when_the_email_already_is_on_the_company(): void
    {
        $this->expectExceptionMessage('The user is already on the company.');

        $company = $this->company();

        (new InviteCompanyMemberAction($company))->execute($company->owner->email, 'member', ['*']);
    }

    /** @test */
    public function throws_when_the_email_is_already_invited_to_the_company(): void
    {
        $this->expectExceptionMessage('The user is already invited to the company.');

        $user    = $this->user();
        $company = $this->company();

        factory(CompanyInvitation::class)->create([
            'company_id' => $company->id,
            'user_id'    => $user->id,
            'email'      => $user->email,
        ]);

        (new InviteCompanyMemberAction($company->fresh()))->execute($user->email, 'member', ['*']);
    }

    /** @test */
    public function can_invite_an_existing_user(): void
    {
        $user    = $this->user();
        $company = $this->company();

        $this->assertDatabaseMissing('company_invitations', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => 'member',
        ]);

        (new InviteCompanyMemberAction($company->fresh()))->execute($user->email, 'member', ['*']);

        $this->assertDatabaseHas('company_invitations', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => 'member',
        ]);

        Mail::assertQueued(InviteExistingUser::class);
    }

    /** @test */
    public function can_invite_a_new_user(): void
    {
        $email    = 'john@doe.com';
        $company  = $this->company();

        $this->assertDatabaseMissing('company_invitations', [
            'email' => $email,
            'role'  => 'member',
        ]);

        (new InviteCompanyMemberAction($company->fresh()))->execute($email, 'member', ['*']);

        $this->assertDatabaseHas('company_invitations', [
            'email' => $email,
            'role'  => 'member',
        ]);

        Mail::assertQueued(InviteNewUser::class);
    }
}
