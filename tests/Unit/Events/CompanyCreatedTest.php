<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Events;

use Andali\Companies\Events\CompanyCreated;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Events\CompanyCreated
 */
class CompanyCreatedTest extends TestCase
{
    /** @test */
    public function can_properly_dispatch_the_event()
    {
        $company = $this->company();

        CompanyCreated::dispatch($company);

        Event::assertDispatched(CompanyCreated::class, fn ($e) => $e->company->id === $company->id);
    }
}
