<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\SubscriptionRemoteRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseController
{
    private SubscriptionRemoteRepository $remoteRepository;

    public function __construct(SubscriptionRemoteRepository $remoteRepository)
    {
        $this->middleware('auth');

        $this->remoteRepository = $remoteRepository;
    }

    public function index(Auth $auth)
    {
        return view(
            'admin.subscriptions.index',
            ['subscriptions' => $this->remoteRepository->getSubscriptionsByCustomer($auth::user()->customer)]
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
