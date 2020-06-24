<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Concerns;

use Andali\Companies\Events\CompanyMemberLeft;
use Andali\Companies\Exceptions\CompanyException;
use Andali\Companies\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Concerns\HasCompanies
 */
class HasCompaniesTest extends TestCase
{
    /** @test */
    public function a_user_has_many_invitations(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->user()->invitations());
    }

    /** @test */
    public function can_determine_if_the_user_has_any_companies(): void
    {
        $company = $this->company();
        $user    = $this->user();

        $this->assertFalse($user->hasCompanies());

        $company->addMember($user, 'member', []);

        $this->assertTrue($user->fresh()->hasCompanies());

        $company->removeMember($user);

        $this->assertFalse($user->fresh()->hasCompanies());
    }

    /** @test */
    public function can_determine_if_the_user_is_on_a_company(): void
    {
        $company = $this->company();
        $user    = $this->user();

        $this->assertFalse($user->onCompany($company));

        $company->addMember($user, 'member', []);

        $this->assertTrue($user->fresh()->onCompany($company));

        $company->removeMember($user);

        $this->assertFalse($user->fresh()->onCompany($company));
    }

    /** @test */
    public function can_determine_if_the_user_owns_a_company(): void
    {
        $user           = $this->user();
        $anotherUser    = $this->user();
        $company        = $this->company($user);

        $this->assertTrue($user->ownsCompany($company));
        $this->assertFalse($anotherUser->ownsCompany($company));
    }

    /** @test */
    public function can_return_all_companies_owned_by_the_user(): void
    {
        $user        = $this->user();
        $anotherUser = $this->user();

        $this->company($user);
        $this->company($user);
        $this->company($anotherUser);

        $this->assertCount(2, $user->ownedCompanies);
    }

    /** @test */
    public function can_determine_what_role_the_user_has_on_a_company(): void
    {
        $user           = $this->user();
        $company        = $this->company();
        $anotherCompany = $this->company();

        $company->addMember($user, 'owner', []);
        $anotherCompany->addMember($user, 'member', []);

        $this->assertSame('owner', $user->roleOn($company));
        $this->assertSame('member', $user->roleOn($anotherCompany));
        $this->assertEmpty($user->roleOn($this->company()));
    }

    /** @test */
    public function can_determine_what_role_the_user_has_on_the_current_company(): void
    {
        $user           = $this->user();
        $company        = $this->company();
        $anotherCompany = $this->company();

        $company->addMember($user, 'owner', []);
        $anotherCompany->addMember($user, 'member', []);

        $user->switchToCompany($company);

        $this->assertSame('owner', $user->roleOnCurrentCompany());

        $user->switchToCompany($anotherCompany);

        $this->assertSame('member', $user->roleOnCurrentCompany());
    }

    /** @test */
    public function can_determine_the_current_company(): void
    {
        $user           = $this->user();
        $company        = $this->company();
        $anotherCompany = $this->company();

        $company->addMember($user, 'owner', []);
        $anotherCompany->addMember($user, 'member', []);

        $user->switchToCompany($company);

        $this->assertSame($company->id, $user->current_company->id);
        $this->assertSame($company->id, $user->currentCompany()->id);

        $user->switchToCompany($anotherCompany);

        $this->assertSame($anotherCompany->id, $user->current_company->id);
        $this->assertSame($anotherCompany->id, $user->currentCompany()->id);

        $user->companies->each->delete();

        $this->assertEmpty($user->fresh()->current_company);
    }

    /** @test */
    public function users_cant_switch_to_companies_they_are_not_on(): void
    {
        $user    = $this->user();
        $company = $this->company();

        $this->expectException(CompanyException::class);

        $user->switchToCompany($company);
    }

    /** @test */
    public function can_determine_if_the_user_owns_the_current_company(): void
    {
        $user        = $this->user();
        $anotherUser = $this->user();

        $this->assertFalse($user->ownsCurrentCompany());

        $company        = $this->company($user);
        $anotherCompany = $this->company($anotherUser);

        $company->addMember($user, 'owner', []);
        $anotherCompany->addMember($anotherUser, 'owner', []);
        $anotherCompany->addMember($user, 'member', []);

        $user->refresh();

        $user->switchToCompany($company);

        $this->assertTrue($user->ownsCurrentCompany());

        $user->switchToCompany($anotherCompany);

        $this->assertFalse($user->ownsCurrentCompany());
    }

    /** @test */
    public function can_refresh_the_current_company(): void
    {
        $user           = $this->user();
        $company        = $this->company();
        $anotherCompany = $this->company();

        $company->addMember($user, 'member', []);
        $anotherCompany->addMember($user, 'member', []);

        $user->switchToCompany($company);

        $this->assertSame($company->id, $user->currentCompany()->id);

        $user->refreshCurrentCompany();

        $this->assertNotNull($user->currentCompany());
    }

    /** @test */
    public function can_leave_a_company(): void
    {
        $user    = $this->user();
        $company = $this->company();

        $company->addMember($user, 'member', []);

        $user->leaveCompany($company);

        Event::assertDispatched(CompanyMemberLeft::class);
    }

    /** @test */
    public function can_not_leave_a_company_if_not_a_member(): void
    {
        $this->expectExceptionMessage('The user does not belong to the given company.');

        $company = $this->company();

        $this->user()->leaveCompany($company);

        Event::assertNotDispatched(CompanyMemberLeft::class);
    }
}
