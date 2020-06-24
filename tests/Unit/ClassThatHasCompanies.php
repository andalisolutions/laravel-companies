<?php

declare(strict_types=1);

namespace Andali\Companies\Tests\Unit;

use Andali\Companies\Concerns\HasCompanies;
use Illuminate\Foundation\Auth\User;

class ClassThatHasCompanies extends User
{
    use HasCompanies;

    public $table = 'users';

    public $guarded = [];
}
