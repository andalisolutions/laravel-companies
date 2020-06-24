<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Models;

use Andali\Companies\Events\CompanyMemberCreated;
use Andali\Companies\Events\CompanyMemberDeleted;
use Andali\Companies\Models\Company;
use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Models\Company
 */
class CompanyTest extends TestCase
{
    /** @test */
    public function can_use_the_configured_table_name(): void
    {
        $company = new Company();

        $this->assertSame(Config::get('companies.tables.companies'), $company->getTable());
    }

    /** @test */
    public function a_company_has_many_invitations(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->company()->invitations());
    }

    /** @test */
    public function a_company_has_pending_invitations(): void
    {
        $user    = $this->user();
        $company = $this->company();

        $this->assertFalse($company->hasPendingInvitation($user->email));

        factory(CompanyInvitation::class)->create([
            'company_id' => $company->id,
            'user_id'    => $user->id,
            'email'      => $user->email,
        ]);

        $this->assertTrue($company->fresh()->hasPendingInvitation($user->email));
    }

    /** @test */
    public function can_determine_the_owners_email_address(): void
    {
        $user    = $this->user();
        $company = $this->company($user);

        $this->assertSame($user->email, $company->email);
    }

    /** @test */
    public function can_determine_if_user_has_access_to_company(): void
    {
        $company        = $this->company();
        $user           = $this->user();
        $anotherUser    = $this->user();

        $company->addMember($anotherUser, 'member', []);

        $this->assertFalse($user->onCompany($company));
        $this->assertTrue($anotherUser->onCompany($company));

        $company->addMember($user, 'member', []);

        $this->assertTrue($user->fresh()->onCompany($company));
        $this->assertTrue($anotherUser->fresh()->onCompany($company));

        $company->removeMember($user);

        $this->assertFalse($user->fresh()->onCompany($company));
        $this->assertTrue($anotherUser->fresh()->onCompany($company));
    }

    /** @test */
    public function proper_share_events_are_fired(): void
    {
        $company        = $this->company();
        $user           = $this->user();
        $anotherUser    = $this->user();

        $company->addMember($user, 'member', []);
        $company->addMember($anotherUser, 'member', []);

        Event::assertDispatched(CompanyMemberCreated::class, fn ($event) => $event->user->id === $user->id);
    }

    /** @test */
    public function proper_unshare_events_are_fired(): void
    {
        $company = $this->company();
        $user    = $this->user();

        $company->addMember($user, 'member', []);
        $company->removeMember($user);

        Event::assertDispatched(CompanyMemberDeleted::class, fn ($event) => $event->user->id === $user->id);
    }

    /** @test */
    public function can_purge_a_company(): void
    {
        $company = $this->company();

        $this->assertDatabaseHas('companies', ['id' => $company->id]);

        $company->purge();

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
