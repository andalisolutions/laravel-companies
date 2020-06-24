<?php

declare(strict_types=1);

namespace Andali\Companies\Actions;

use Andali\Companies\Exceptions\CompanyException;
use Andali\Companies\Mail\InviteExistingUser;
use Andali\Companies\Mail\InviteNewUser;
use Andali\Companies\Models\Company;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class InviteCompanyMemberAction
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function execute(string $email, string $role, array $permissions): void
    {
        if ($this->emailAlreadyOnCompany($email)) {
            throw CompanyException::emailAlreadyOnCompany();
        }

        if ($this->emailAlreadyInvited($email)) {
            throw CompanyException::emailAlreadyInvited();
        }

        $invitedUser = $this->findInvitedUser($email);

        $invitation = $this->company->invitations()->create([
            'user_id'      => $invitedUser ? $invitedUser->id : null,
            'role'         => $role,
            'permissions'  => $permissions,
            'email'        => $email,
            'accept_token' => Uuid::uuid4(),
            'reject_token' => Uuid::uuid4(),
        ]);

        $mail = Mail::to($invitation->email);

        if ($invitation->user_id) {
            $mail->send(new InviteExistingUser($invitation));
        } else {
            $mail->send(new InviteNewUser($invitation));
        }
    }

    private function emailAlreadyOnCompany(string $email): bool
    {
        return $this->company->members()->where('email', $email)->exists();
    }

    private function emailAlreadyInvited(string $email): bool
    {
        return $this->company->hasPendingInvitation($email);
    }

    private function findInvitedUser(string $email): ?Authenticatable
    {
        $userModel = Config::get('companies.models.user');

        return $userModel::where('email', $email)->first();
    }
}
