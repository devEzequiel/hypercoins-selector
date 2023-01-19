<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvailableCard extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable  = [
        'client_id',
        'value'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
