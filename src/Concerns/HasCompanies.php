<?php

declare(strict_types=1);

namespace Andali\Companies\Concerns;

use Andali\Companies\Contracts\Company;
use Andali\Companies\Events\CompanyMemberLeft;
use Andali\Companies\Exceptions\CompanyException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

trait HasCompanies
{
    public function invitations(): HasMany
    {
        return $this->hasMany(Config::get('companies.models.invitation'));
    }

    public function hasCompanies(): bool
    {
        return $this->companies->isNotEmpty();
    }

    public function companies(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Config::get('companies.models.company'),
                'company_users',
                'user_id',
                'company_id'
            )
            ->using(Config::get('companies.models.member'))
            ->withPivot(['role', 'permissions'])
            ->orderBy('name', 'asc');
    }

    public function onCompany(Company $company): bool
    {
        return $this->companies->contains($company);
    }

    public function ownsCompany(Company $company): bool
    {
        return $this->id && $company->owner_id && $this->id === $company->owner_id;
    }

    public function ownedCompanies(): HasMany
    {
        return $this->hasMany(Config::get('companies.models.company'), 'owner_id');
    }

    public function roleOn(Company $company): ?string
    {
        if ($company = $this->companies->find($company->id)) {
            return $company->pivot->role;
        }

        return null;
    }

    public function roleOnCurrentCompany(): string
    {
        return $this->roleOn($this->currentCompany);
    }

    public function getCurrentCompanyAttribute(): ?Company
    {
        return $this->currentCompany();
    }

    public function currentCompany(): ?Company
    {
        if (! $this->hasCompanies()) {
            return null;
        }

        if (! is_null($this->current_company_id)) {
            $currentCompany = $this->companies->find($this->current_company_id);

            return $currentCompany ?: $this->refreshCurrentCompany();
        }

        $this->switchToCompany($this->companies()->first());

        return $this->currentCompany();
    }

    public function ownsCurrentCompany(): bool
    {
        $currentCompany = $this->currentCompany();

        if (! $currentCompany) {
            return false;
        }

        $ownerId = (int) $currentCompany->owner_id;

        return $currentCompany && $ownerId === $this->id;
    }

    public function switchToCompany(Company $company): void
    {
        if (! $this->onCompany($company)) {
            throw CompanyException::doesNotBelongToCompany();
        }

        $this->current_company_id = $company->id;

        $this->save();
    }

    public function refreshCurrentCompany(): ?Company
    {
        $this->current_company_id = null;

        $this->save();

        return $this->currentCompany();
    }

    public function leaveCompany(Company $company): void
    {
        if (! $this->onCompany($company)) {
            throw CompanyException::doesNotBelongToCompany();
        }

        $company->removeMember($this);

        CompanyMemberLeft::dispatch($company, $this);
    }
}
