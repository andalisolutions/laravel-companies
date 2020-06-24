<?php

use Andali\Companies\Models\Company;
use Andali\Companies\Tests\Unit\ClassThatHasCompanies;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'owner_id'       => factory(ClassThatHasCompanies::class),
        'name'           => $faker->unique()->firstName,
        'vat_number'     => $faker->unique()->numberBetween(123, 999999),
        'address'        => $faker->address,
        'tax_payer'      => $faker->boolean,
    ];
});
