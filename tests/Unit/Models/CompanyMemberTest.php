<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Models;

use Andali\Companies\Models\CompanyMember;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Config;

/**
 * @covers \Andali\Companies\Models\CompanyMember
 */
class CompanyMemberTest extends TestCase
{
    /** @test */
    public function can_use_the_configured_table_name(): void
    {
        $member = new CompanyMember();

        $this->assertSame(Config::get('companies.tables.members'), $member->getTable());
    }
}
