<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'password'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
