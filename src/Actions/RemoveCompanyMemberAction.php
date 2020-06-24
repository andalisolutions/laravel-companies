<?php

declare(strict_types=1);

namespace Andali\Companies\Actions;

use Andali\Companies\Exceptions\CompanyException;
use Andali\Companies\Models\Company;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class RemoveCompanyMemberAction
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function execute(Authenticatable $member): void
    {
        if ($this->isOwner($member)) {
            throw CompanyException::canNotRemoveOwner();
        }

        $this->company->removeMember($member);
    }

    private function isOwner(Authenticatable $member): bool
    {
        $currentUser = Auth::user();

        return $currentUser->ownsCompany($this->company) && $currentUser->id === $member->id;
    }
}
