<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Http\Middleware;

use Andali\Companies\Http\Middleware\VerifyUserHasCompany;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Route;

/**
 * @covers \Andali\Companies\Http\Middleware\VerifyUserHasCompany
 */
class VerifyUserHasCompanyTest extends TestCase
{
    /** @test */
    public function redirects_the_user_if_it_doesnt_have_companies(): void
    {
        Route::middleware(VerifyUserHasCompany::class)->get('/', fn () => []);

        $this->actingAs($this->user())->get('/')->assertRedirect();
    }

    /** @test */
    public function fulfils_the_request_if_the_user_has_companies(): void
    {
        Route::middleware(VerifyUserHasCompany::class)->get('/', fn () => []);

        $this->actingAs($user = $this->user());

        $this->company($user)->addMember($user, 'owner', []);

        $this->get('/')->assertOk();
    }
}
