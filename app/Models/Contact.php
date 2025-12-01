<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'Contacts';

    public $timestamps = false;

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'update_at';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_domain',
        'company_id',
        'company_name',
        'created_at',
        'update_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'update_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'contact_id');
    }

    public function primaryTicket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'contact_id');
    }

    public function ticketByEmail(): HasOne
    {
        return $this->hasOne(Ticket::class, 'email', 'contact_email');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'contact_id');
    }
}
