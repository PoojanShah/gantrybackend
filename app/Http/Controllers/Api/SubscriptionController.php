<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class SubscriptionController extends BaseController
{
    public function createOrUpdate(Request $request): Subscription
    {
        $data = $request->get('data');

        $subscription = Subscription::where('zoho_customer_id', '=', $data['customer_id'])
            ->where('zoho_subscription_id', '=', $data['subscription_id'])
            ->first();
        $subscription = $subscription  ?? new Subscription();
        $subscription->zoho_subscription_id = $data['subscription']['subscription_id'];
        $subscription->zoho_product_id = $data['subscription']['product_id'];
        $subscription->zoho_plan_id = $data['subscription']['plan_id'];
        $subscription->previous_subscription_status = $data['subscription']['previous_subscription_status'];
        $subscription->subscription_status = $data['subscription']['subscription_status'];
        $subscription->modified_at = $data['subscription']['modified_at'];
        $subscription->modified_at = $data['subscription']['zoho_customer_id'];

        $customer = Customer::where('zoho_customer_id', $data['customer_id'])->first();
        if($customer){
            $customer->videos()->detach();
        } else {
            $customer = new Customer();
            $customer->zoho_customer_id = $data['customer_id'];
            $customer->installation_id = $data['customer']['cf_installation_id'];
            $customer->display_name = $data['customer']['display_name'];
            $customer->save();
        }

        if (isset($data['subscription']['addons'])){
            foreach ($data['addons'] as $addon){
                if($video = Video::where('zoho_addon_code', $addon['addon_code'])->first()){
                    $customer->videos()->attach($video->id);
                }
            }
        }

        return $subscription;
    }


    public function getClientSubscription(Request $request, string $installationId): ?Subscription
    {
        $token = $request->token;
        if($token === env('API_TOKEN')) { // TODO move if to middleware
            return Subscription::whereHas('customer', function ($query) use ($installationId) {
                return $query->where('installation_id', '=', $installationId);
            })->first();
        }

        return response('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

}