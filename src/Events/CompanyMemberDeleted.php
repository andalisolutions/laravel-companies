<?php

declare(strict_types=1);

namespace Andali\Companies\Events;

use Andali\Companies\Contracts\Company;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;

class CompanyMemberDeleted
{
    use Dispatchable;

    public Company $company;

    public Authenticatable $user;

    public function __construct(Company $company, Authenticatable $user)
    {
        $this->company = $company;
        $this->user    = $user;
    }
}
