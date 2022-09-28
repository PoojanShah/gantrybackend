<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    public $fillable = [
        'zoho_customer_id',
        'installation_id',
        'display_name'
    ];

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }
}
