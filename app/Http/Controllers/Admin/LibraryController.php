<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use App\Models\Video;
use App\Repositories\AddonRemoteRepository;
use App\Repositories\SubscriptionRemoteRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class LibraryController extends BaseController
{
    private SubscriptionRemoteRepository $remoteRepository;

    public function __construct(SubscriptionRemoteRepository $remoteRepository)
    {
        $this->middleware('auth');

        $this->remoteRepository = $remoteRepository;
    }

    public function index(Auth $auth)
    {
        $activeSubscription = $auth::user()->customer->getActiveSubscription();

        if($activeSubscription->plan_code === config('zoho.ZOHO_ALL_INCLUSIVE_PLAN_CODE')){
            return view(
                'admin.library.indexAllInclusive',
                [
                    'media' => Video::where('status', '=', Video::STATUS_ACTIVE)->get(),
                ]
            );
        } else {
            $subscription = $this->remoteRepository
                ->getOne($activeSubscription->zoho_subscription_id);

            foreach ($subscription->addons as $addon) {
                $addon->video = Video::where('zoho_addon_code', '=', $addon->addon_code)->first();
            }

            $availableForSubscription = Video::whereNotNull('zoho_addon_code')
                ->whereDoesntHave('customers', function (Builder $query) use ($auth) {
                    $query->where('customers.id', '=', $auth::user()->customer_id);
                })
                ->get();

            return view(
                'admin.library.indexAddonBasedPlan',
                [
                    'subscription' => $subscription,
                    'freeMedia' => Video::whereNull('zoho_addon_code')->where('status', '=', 1)->get(),
                    'availableForSubscription' => $availableForSubscription,
                ]
            );
        }
    }

    public function show(Video $media, AddonRemoteRepository $addonRemoteRepository)
    {
        return view(
            'admin.library.show',
            [
                'media' => $media,
                'isAddonPayed' => $media->isAddonPayedByUser(Auth::user()),
                'zohoAddon' => $media->zoho_addon_code  ? $addonRemoteRepository->getOne($media->zoho_addon_code) : null
            ]
        );
    }

    public function subscribeAddon(Video $media, AddonRemoteRepository $addonRemoteRepository, SubscriptionRemoteRepository $subscriptionRemoteRepository)
    {
        $subscription = Subscription::where('customer_id', '=', Auth::user()->customer_id)
            ->where('subscription_status', '=', SubscriptionRemoteRepository::LIVE)
            ->first();

        $addonRemoteRepository->addRemoveSubscriptionAddon(
            $subscriptionRemoteRepository->getOne($subscription->zoho_subscription_id),
            [$media->zoho_addon_code]
        );


    }
    public function reactivateSubscription(int $id)
    {
        $this->remoteRepository->reactivateSubscription($id);

        return redirect()->back();
    }

}
