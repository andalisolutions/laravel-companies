<?php

declare(strict_types=1);

namespace Andali\Companies\Tests;

use Andali\Companies\Models\Company;
use Andali\Companies\Providers\CompaniesServiceProvider;
use Andali\Companies\Tests\Unit\ClassThatHasCompanies;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
        Mail::fake();

        $this->migrate();

        $this->withFactories(realpath(__DIR__.'/Factories'));
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('companies.models.user', ClassThatHasCompanies::class);
    }

    protected function getPackageProviders($app): array
    {
        return [CompaniesServiceProvider::class];
    }

    protected function migrate(): void
    {
        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function user(): ClassThatHasCompanies
    {
        return ClassThatHasCompanies::create([
            'name'     => $this->faker->name,
            'email'    => $this->faker->safeEmail,
            'password' => $this->faker->password,
        ]);
    }

    protected function company(?ClassThatHasCompanies $user = null): Company
    {
        $user = $user ?: $this->user();

        $company = Company::create([
            'owner_id'   => $user->id,
            'name'       => 'Andali Solutions Pro SRL',
            'vat_number' => '38744563',
            'address'    => 'str. Sotcan, nr. 940, Leresti, jud. Arges',
             'tax_payer' => false,
        ]);

        $company->addMember($user, 'owner', []);

        return $company;
    }
}
