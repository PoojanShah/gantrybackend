<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class Video extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 1;

    public $table = 'video';

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }

    public function getAvailableVideosObjects(?Subscription $subscription): Collection
    {
        $installationId = $subscription ? $subscription->customer->installation_id : null;

        $query = self::select('video.*')->where('status', '=', 1);

        if ($installationId && $subscription->plan_code !==  config('zoho.ZOHO_ALL_INCLUSIVE_PLAN_CODE')) {
            $query->leftJoin('customer_video', 'video.id', '=', 'customer_video.video_id')
                ->leftJoin('customers', 'customer_video.customer_id', '=', 'customers.id')
                ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
                ->whereIn('subscriptions.subscription_status', Subscription::ACTIVE_STATUSES)
                ->where('customers.installation_id', '=', $installationId)
                ->orWhereNull('video.zoho_addon_code');
        } elseif($installationId === null) {
            $query->whereNull('video.zoho_addon_code');
        }

        return static::hydrate(
            $query->distinct('video.id')->orderBy('video.zoho_addon_code')->get()->toArray()
        );
    }

    public function isAddonPayedByUser(User $user): bool
    {
        return (bool)$this->where('video.id', '=', $this->id)
            ->whereHas('customers', function (Builder $query) use ($user) {
                $query->where('customers.id', '=', $user->customer_id);
            })->first();
    }
}
