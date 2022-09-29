<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function subscription(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function getActiveSubscription(string $installationId)
    {
        return  DB::table('subscriptions')->select('subscriptions.*')
            ->leftJoin('customers', 'subscriptions.customer_id',  '=', 'customers.id')
            ->whereIn('subscriptions.subscription_status',Subscription::ACTIVE_STATUSES)
            ->where('customers.installation_id', '=', $installationId)
            ->first();
    }

}
