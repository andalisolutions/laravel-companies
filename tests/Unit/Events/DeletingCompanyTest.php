<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Events;

use Andali\Companies\Events\DeletingCompany;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Event;

/**
 * @covers \Andali\Companies\Events\DeletingCompany
 */
class DeletingCompanyTest extends TestCase
{
    /** @test */
    public function can_properly_dispatch_the_event()
    {
        $company = $this->company();

        DeletingCompany::dispatch($company);

        Event::assertDispatched(DeletingCompany::class, fn ($e) => $e->company->id === $company->id);
    }
}
