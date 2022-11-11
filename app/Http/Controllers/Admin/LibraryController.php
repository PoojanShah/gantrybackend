<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use App\Repositories\AddonRemoteRepository;
use App\Repositories\SubscriptionRemoteRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LibraryController extends BaseController
{
    private SubscriptionRemoteRepository $remoteRepository;

    public function __construct(SubscriptionRemoteRepository $remoteRepository)
    {
        $this->middleware('auth');

        $this->remoteRepository = $remoteRepository;
    }

    public function index(Auth $auth, AddonRemoteRepository $addonRemoteRepository)
    {
        $activeSubscription = $auth::user()->customer->getActiveSubscription();

        if ($activeSubscription->plan_code === config('zoho.ZOHO_ALL_INCLUSIVE_PLAN_CODE')) {
            return view(
                'admin.library.indexAllInclusive',
                [
                    'media' => Video::where('status', '=', Video::STATUS_ACTIVE)->get(),
                ]
            );
        } else {
            $subscription = $this->remoteRepository
                ->getOne($activeSubscription->zoho_subscription_id);

            $availableForPurchasingAddons = [];
            $notCustomerMedia = Video::whereNotNull('zoho_addon_code')
                ->whereDoesntHave('customers', function (Builder $query) use ($auth) {
                    $query->where('customers.id', '=', $auth::user()->customer_id);
                })
                ->get()
                ->keyBy('zoho_addon_code');

            foreach ($addonRemoteRepository->getAddonsByPlanCode($activeSubscription->plan_code, 'ONETIME') as $addon) {
                if ($media = $notCustomerMedia->get($addon->addon_code)) {
                    $addon->video = $media;
                    $availableForPurchasingAddons[] = $addon;
                }
            }

            return view(
                'admin.library.indexAddonBasedPlan',
                [
                    'subscription' => $subscription,
                    'freeMedia' => Video::whereNull('zoho_addon_code')->where('status', '=', 1)->get(),
                    'purchasedMedia' => Video::whereNotNull('zoho_addon_code')
                        ->whereHas('customers', function (Builder $query) use ($auth) {
                            $query->where('customers.id', '=', $auth::user()->customer_id);
                        })
                        ->get(),
                    'availableForPurchasingAddons' => $availableForPurchasingAddons,
                ]
            );
        }
    }

    public function show(Video $media, AddonRemoteRepository $addonRemoteRepository, Auth $auth)
    {
        $activeSubscription = $auth::user()->customer->getActiveSubscription();

        return view(
            'admin.library.show',
            [
                'media' => $media,
                'isAddonPayed' => $media->isAddonPayedByUser(Auth::user()),
                'zohoAddon' => $media->zoho_addon_code ? $addonRemoteRepository->getOne($media->zoho_addon_code) : null,
                'subscription' => $this->remoteRepository
                    ->getOne($activeSubscription->zoho_subscription_id)
            ]
        );
    }

    public function buyOneTimeAddon(Video $media, AddonRemoteRepository $addonRemoteRepository, Request $request)
    {
        if ($addonRemoteRepository->buyOneTimeAddons(
            Auth::user()->customer->getActiveSubscription(),
            [$media->zoho_addon_code]
        )) {
            Auth::user()->customer->videos()->attach($media->id);
            Session::flash('status', 'success');
            Session::flash('message', 'Successfully added onetime addon. An invoice was sent to your email.');
        } else {
            Session::flash('status', 'error');
            Session::flash('message', 'The addon has not been added due to an error. Contact with administrator!');
        }

        return redirect()->route('library.index');
    }
}
