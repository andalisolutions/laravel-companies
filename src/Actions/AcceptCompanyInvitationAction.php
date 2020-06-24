<?php

declare(strict_types=1);

namespace Andali\Companies\Actions;

use Andali\Companies\Exceptions\CompanyInvitationException;
use Andali\Companies\Models\CompanyInvitation;
use Illuminate\Support\Facades\Auth;

class AcceptCompanyInvitationAction
{
    private CompanyInvitation $invitation;

    public function __construct(CompanyInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function execute(): void
    {
        $expectedUser = Auth::id() === (int) $this->invitation->user_id;

        if (! $expectedUser) {
            throw CompanyInvitationException::attemptedClaimByUnauthorizedUser();
        }

        $this->invitation->company->addMember(
            Auth::user(),
            $this->invitation->role,
            $this->invitation->permissions
        );

        $this->invitation->delete();
    }
}
