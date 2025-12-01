<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    use HasFactory;

    protected $table = 'History';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'description',
        'contact_id',
        'company_id',
        'ticket_id',
        'user',
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function ticket(): BelongsTo
    {
        // History keeps the external ticket reference, not the auto-increment id.
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
