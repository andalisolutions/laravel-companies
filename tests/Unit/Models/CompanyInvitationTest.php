<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Models;

use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

/**
 * @covers \Andali\Companies\Models\CompanyInvitation
 */
class CompanyInvitationTest extends TestCase
{
    /** @test */
    public function can_use_the_configured_table_name(): void
    {
        $invitation = new CompanyInvitation();

        $this->assertSame(Config::get('companies.tables.invitations'), $invitation->getTable());
    }

    /** @test */
    public function an_invitation_belongs_to_a_company_and_user()
    {
        $user          = $this->user();
        $company       = $this->company();
        $invitation    = factory(CompanyInvitation::class)->create([
            'company_id' => $company->id,
            'user_id'    => $user->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $invitation->company());
        $this->assertInstanceOf(BelongsTo::class, $invitation->user());
        $this->assertSame($company->id, $invitation->company_id);
        $this->assertSame($user->id, $invitation->user_id);
    }

    /** @test */
    public function can_determine_if_the_invitation_is_expired()
    {
        $invitation             = new CompanyInvitation();
        $invitation->created_at = Carbon::now()->subWeeks(2);

        $this->assertTrue($invitation->isExpired());

        $invitation->created_at = Carbon::now()->addWeeks(2);

        $this->assertFalse($invitation->isExpired());
    }

    /** @test */
    public function can_find_an_invitation_by_its_accept_token(): void
    {
        $invitation = factory(CompanyInvitation::class)->create();

        $this->assertSame($invitation->id, CompanyInvitation::findByAcceptToken($invitation->accept_token)->id);
    }

    /** @test */
    public function can_find_an_invitation_by_its_reject_token(): void
    {
        $invitation = factory(CompanyInvitation::class)->create();

        $this->assertSame($invitation->id, CompanyInvitation::findByRejectToken($invitation->reject_token)->id);
    }
}
