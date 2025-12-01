<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $table = 'Companies';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'company_name',
        'company_country',
        'company_region',
        'company_city',
        'company_zip_code',
        'company_address',
        'company_VAT_ID',
        'company_description',
        'domain',
        'contact_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'company_id');
    }

    public function contactsByDomain(): HasMany
    {
        return $this->hasMany(Contact::class, 'contact_domain', 'domain');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'company_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'company_id');
    }
}
