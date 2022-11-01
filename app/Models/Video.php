<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Video extends Model
{
    use HasFactory;

    public $table = 'video';

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }

    public function getAvailableVideosObjects(?string $installationId): Collection
    {
        $query = DB::table($this->table)->select('video.*')->where('status', '=', 1);

        if ($installationId) {
            $query->leftJoin('customer_video', 'video.id',  '=', 'customer_video.video_id')
                ->leftJoin('customers', 'customer_video.customer_id',  '=', 'customers.id')
                ->leftJoin('subscriptions', 'customers.id',  '=', 'subscriptions.customer_id')
                ->whereIn('subscriptions.subscription_status',Subscription::ACTIVE_STATUSES)
                ->where('customers.installation_id', '=', $installationId)
                ->orWhereNull('video.zoho_addon_code');
        } else {
            $query = DB::table($this->table)
                ->whereNull('video.zoho_addon_code');
        }

        return static::hydrate(
            $query->where('status', '=', 1)->distinct('video.id')->orderBy('sort')->get()->toArray()
        );
    }

}
