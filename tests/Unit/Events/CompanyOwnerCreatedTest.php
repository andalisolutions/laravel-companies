<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Events;

use Andali\Companies\Events\CompanyOwnerCreated;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Events\CompanyOwnerCreated
 */
class CompanyOwnerCreatedTest extends TestCase
{
    /** @test */
    public function can_properly_dispatch_the_event()
    {
        $this->migrate();

        Event::fake();

        $user    = $this->user();
        $company = $this->company($user);

        CompanyOwnerCreated::dispatch($company, $user);

        Event::assertDispatched(CompanyOwnerCreated::class, fn ($e) => $e->user->id === $user->id);
    }
}
