<?php

declare(strict_types=1);

namespace Andali\Companies\Models;

use Andali\Companies\Contracts\CompanyMember as Contract;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Config;

class CompanyMember extends Pivot implements Contract
{
    protected $table = 'company_users';

    protected $casts = ['permissions' => 'json'];

    public function getTable(): string
    {
        return Config::get('companies.tables.members');
    }
}
