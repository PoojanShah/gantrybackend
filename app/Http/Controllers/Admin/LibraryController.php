<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use App\Repositories\SubscriptionRemoteRepository;
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
        $subscription = $this->remoteRepository
            ->getOne($auth::user()->customer->getActiveSubscription()->zoho_subscription_id);

        foreach ($subscription->addons as $addon){
            $addon->video = Video::where('zoho_addon_code', '=', $addon->addon_code)->first();
        }

        $freeMedia = Video::whereNull('zoho_addon_code')->where('status', '=', 1)->get();

        return view(
            'admin.library.index',
            [
                'subscription' => $subscription,
                'freeMedia' => $freeMedia,
            ]
        );
    }

    public function show(int $id)
    {
        return view('admin.subscriptions.show', ['subscription' => $this->remoteRepository->getOne($id)]);
    }

    public function reactivateSubscription(int $id)
    {
        $this->remoteRepository->reactivateSubscription($id);
        return redirect()->back();
    }

}
