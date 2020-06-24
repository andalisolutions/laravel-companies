<?php

declare(strict_types=1);

namespace Andali\Companies\Actions;

use Andali\Companies\Models\Company;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateCompanyMemberAction
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function execute(Authenticatable $member, string $role, array $permissions): void
    {
        $this->company->members()->updateExistingPivot($member, compact('role', 'permissions'));
    }
}
