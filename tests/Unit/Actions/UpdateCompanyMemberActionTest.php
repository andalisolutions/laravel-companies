<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Actions;

use Andali\Companies\Actions\UpdateCompanyMemberAction;
use Andali\Companies\Tests\TestCase;

/**
 * @covers \Andali\Companies\Actions\UpdateCompanyMemberAction
 */
class UpdateCompanyMemberActionTest extends TestCase
{
    /** @test */
    public function can_update_the_company_member(): void
    {
        $user    = $this->user();
        $company = $this->company();

        $company->addMember($user, 'member', []);

        $this->actingAs($company->owner);

        $this->assertDatabaseMissing('company_users', [
            'user_id'     => $user->id,
            'role'        => 'moderator',
            'permissions' => json_encode(['all']),
        ]);

        (new UpdateCompanyMemberAction($company))->execute($user, 'moderator', ['all']);

        $this->assertDatabaseHas('company_users', [
            'user_id'     => $user->id,
            'role'        => 'moderator',
            'permissions' => json_encode(['all']),
        ]);
    }
}
