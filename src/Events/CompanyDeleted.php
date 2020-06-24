<?php

declare(strict_types=1);

namespace Andali\Companies\Events;

use Andali\Companies\Contracts\Company;
use Illuminate\Foundation\Events\Dispatchable;

class CompanyDeleted
{
    use Dispatchable;

    public Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }
}
