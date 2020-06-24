<?php

declare(strict_types=1);

namespace Andali\Companies\Models\Concerns;

use Andali\Companies\Events\CompanyMemberCreated;
use Andali\Companies\Events\CompanyMemberDeleted;
use Andali\Companies\Events\CompanyOwnerCreated;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Config;

trait HasMembers
{
    public function members(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Config::get('companies.models.user'),
                Config::get('companies.tables.members'),
                'company_id',
                'user_id'
            )
            ->using(Config::get('companies.models.member'))
            ->withPivot(['role', 'permissions']);
    }

    public function addMember($user, string $role = 'member', array $permissions = []): void
    {
        $this->members()->detach($user);

        $this->members()->attach($user, compact('role', 'permissions'));

        unset($this->members);

        CompanyMemberCreated::dispatch($this, $user);

        if ($role === 'owner') {
            CompanyOwnerCreated::dispatch($this, $user);
        }
    }

    public function removeMember($user): void
    {
        $this->members()->detach($user);

        CompanyMemberDeleted::dispatch($this, $user);
    }
}
