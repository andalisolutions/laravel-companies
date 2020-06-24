<?php

declare(strict_types=1);

namespace Andali\Companies\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

trait HasInvitations
{
    public function invitations(): HasMany
    {
        return $this->hasMany(Config::get('companies.models.invitation'));
    }

    public function hasPendingInvitation(string $email): bool
    {
        return $this->invitations()->where('email', $email)->exists();
    }
}
