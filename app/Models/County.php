<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'badge',
        'name',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'id_county');
    }
}