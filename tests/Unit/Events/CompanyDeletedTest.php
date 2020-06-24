<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Events;

use Andali\Companies\Events\CompanyDeleted;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Events\CompanyDeleted
 */
class CompanyDeletedTest extends TestCase
{
    /** @test */
    public function can_properly_dispatch_the_event()
    {
        $company = $this->company();

        CompanyDeleted::dispatch($company);

        Event::assertDispatched(CompanyDeleted::class, fn ($e) => $e->company->id === $company->id);
    }
}
