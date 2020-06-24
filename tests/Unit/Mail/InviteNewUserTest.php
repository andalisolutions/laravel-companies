<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit\Mail;

use Andali\Companies\Mail\InviteNewUser;
use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * @covers \Andali\Companies\Mail\InviteNewUser
 */
class InviteNewUserTest extends TestCase
{
    /** @test */
    public function sends_the_mail_to_the_invited_user()
    {
        [$user, $company, $invitation] = $this->createModels();

        Mail::to($user)->send(new InviteNewUser($invitation));

        Mail::assertQueued(InviteNewUser::class, fn ($mail) => $mail->hasTo($user->email));
    }

    /** @test */
    public function builds_the_mail_with_the_correct_subject()
    {
        [$user, $company, $invitation] = $this->createModels();

        $mail = new InviteNewUser($invitation);

        $this->assertSame('New Invitation!', $mail->build()->subject);
    }

    private function createModels(): array
    {
        $user    = $this->user();
        $company = $this->company($user);

        $invitation = CompanyInvitation::create([
            'company_id'      => $company->id,
            'user_id'         => $user->id,
            'email'           => $user->email,
            'role'            => 'member',
            'permissions'     => [],
            'accept_token'    => Str::random(40),
            'reject_token'    => Str::random(40),
        ]);

        return [$user, $company, $invitation];
    }
}
