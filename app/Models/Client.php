<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
      'balance', 'limit'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'client_id')->orderByDesc('created_at')->limit(10);
    }

}
