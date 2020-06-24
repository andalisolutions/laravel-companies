<?php

declare(strict_types=1);

namespace Andali\Companies\Models;

use Andali\Companies\Contracts\Company as Contract;
use Andali\Companies\Events\CompanyCreated;
use Andali\Companies\Events\CompanyDeleted;
use Andali\Companies\Events\DeletingCompany;
use Andali\Companies\Models\Concerns\HasInvitations;
use Andali\Companies\Models\Concerns\HasMembers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

class Company extends Model implements Contract
{
    use HasInvitations;
    use HasMembers;

    protected $fillable = ['owner_id', 'name', 'vat_number', 'address', 'tax_payer'];

    protected $casts = [
        'owner_id'  => 'integer',
        'tax_payer' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created'  => CompanyCreated::class,
        'deleted'  => CompanyDeleted::class,
        'deleting' => DeletingCompany::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Config::get('companies.models.user'), 'owner_id');
    }

    public function purge(): void
    {
        $this
            ->members()
            ->where('current_company_id', $this->id)
            ->update(['current_company_id' => null]);

        $this->members()->detach();

        $this->delete();
    }

    public function getEmailAttribute(): string
    {
        return $this->owner->email;
    }

    public function getTable(): string
    {
        return Config::get('companies.tables.companies');
    }
}
