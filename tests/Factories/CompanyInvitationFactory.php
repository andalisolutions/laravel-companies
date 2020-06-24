<?php

use Andali\Companies\Models\Company;
use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Tests\Unit\ClassThatHasCompanies;
use Faker\Generator as Faker;

$factory->define(CompanyInvitation::class, function (Faker $faker) {
    return [
        'company_id'      => factory(Company::class),
        'user_id'         => factory(ClassThatHasCompanies::class),
        'email'           => $faker->email,
        'role'            => 'member',
        'permissions'     => [],
        'accept_token'    => $faker->uuid,
        'reject_token'    => $faker->uuid,
    ];
});
