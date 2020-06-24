<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Events;

use Andali\Companies\Events\UserInvitedToCompany;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Events\UserInvitedToCompany
 */
class UserInvitedToCompanyTest extends TestCase
{
    /** @test */
    public function can_properly_dispatch_the_event()
    {
        $this->migrate();

        Event::fake();

        $user    = $this->user();
        $company = $this->company($user);

        UserInvitedToCompany::dispatch($company, $user);

        Event::assertDispatched(UserInvitedToCompany::class, fn ($e) => $e->user->id === $user->id);
    }
}
