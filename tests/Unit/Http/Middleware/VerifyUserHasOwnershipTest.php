<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Http\Middleware;

use Andali\Companies\Http\Middleware\VerifyUserHasOwnership;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Route;

/**
 * @covers \Andali\Companies\Http\Middleware\VerifyUserHasOwnership
 */
class VerifyUserHasOwnershipTest extends TestCase
{
    /** @test */
    public function aborts_the_request_if_the_user_doesnt_have_ownership(): void
    {
        Route::middleware(VerifyUserHasOwnership::class)->get('/', fn () => []);

        $user    = $this->user();
        $company = $this->company();

        $company->addMember($user, 'member', []);

        $this
            ->actingAs($user)
            ->get('/')
            ->assertForbidden();
    }

    /** @test */
    public function aborts_the_request_if_the_user_doesnt_have_any_companies(): void
    {
        Route::middleware(VerifyUserHasOwnership::class)->get('/', fn () => []);

        $this
            ->actingAs($this->user())
            ->get('/')
            ->assertForbidden();
    }

    /** @test */
    public function fulfils_the_request_if_the_user_has_ownership(): void
    {
        Route::middleware(VerifyUserHasOwnership::class)->get('/', fn () => []);

        $this
            ->actingAs($this->company()->owner)
            ->get('/')
            ->assertOk();
    }
}
