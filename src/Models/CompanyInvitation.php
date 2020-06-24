<?php

declare(strict_types=1);

namespace Andali\Companies\Models;

use Andali\Companies\Contracts\CompanyInvitation as Contract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

class CompanyInvitation extends Model implements Contract
{
    protected $fillable = ['company_id', 'user_id', 'email', 'role', 'permissions', 'accept_token', 'reject_token'];

    protected $casts = ['permissions' => 'json'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Config::get('companies.models.company'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Config::get('companies.models.user'));
    }

    public function isExpired(): bool
    {
        return Carbon::now()->subWeek()->gte($this->created_at);
    }

    public static function findByAcceptToken(string $token): self
    {
        return static::where('accept_token', $token)->firstOrFail();
    }

    public static function findByRejectToken(string $token): self
    {
        return static::where('reject_token', $token)->firstOrFail();
    }

    public function getTable(): string
    {
        return Config::get('companies.tables.invitations');
    }
}
