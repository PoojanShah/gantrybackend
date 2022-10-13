<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    public const ACTIVE_STATUSES = [
        'live',
        'trial'
    ];

    protected $fillable = [
        'zoho_subscription_id',
        'zoho_product_id',
        'plan_code',
        'previous_subscription_status',
        'subscription_status',
        'modified_at',
        'zoho_customer_id',
        'customer_id',
        'customer_email'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
