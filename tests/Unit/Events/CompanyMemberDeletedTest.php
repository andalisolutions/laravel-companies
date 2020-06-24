<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Events;

use Andali\Companies\Events\CompanyMemberDeleted;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Events\CompanyMemberDeleted
 */
class CompanyMemberDeletedTest extends TestCase
{
    /** @test */
    public function can_properly_dispatch_the_event()
    {
        $this->migrate();

        Event::fake();

        $user    = $this->user();
        $company = $this->company($user);

        CompanyMemberDeleted::dispatch($company, $user);

        Event::assertDispatched(CompanyMemberDeleted::class, fn ($e) => $e->user->id === $user->id);
    }
}
