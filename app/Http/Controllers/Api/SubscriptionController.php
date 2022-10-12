<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SubscriptionController extends BaseController
{
    public function createOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            $subscriptionData = $request->post('subscription') ?? $request->post('data')['subscription'];
            $customerData = $subscriptionData['customer'];

            $subscription = Subscription::where('zoho_customer_id', '=', $customerData['customer_id'])
                ->where('zoho_subscription_id', '=', $subscriptionData['subscription_id'])
                ->first();

            $customer = Customer::where('zoho_customer_id', $customerData['customer_id'])->first();

            if ($customer) {
                $customer->videos()->detach();
            } else {
                $customer = new Customer();
                $customer->zoho_customer_id = $customerData['customer_id'];
                $customer->installation_id = $customerData['cf_installation_id'];
                $customer->display_name = $customerData['display_name'];
                $customer->save();
            }

            $subscription = $subscription ?? new Subscription();
            $subscription->customer_id = $customer->id;
            $subscription->zoho_subscription_id = $subscriptionData['subscription_id'];
            $subscription->zoho_product_id = $subscriptionData['product_id'];
            $subscription->plan_code = $subscriptionData['plan']['plan_code'];

            $subscription->previous_subscription_status = $subscriptionData['previous_attribute']['status'] ?? null;
            $subscription->subscription_status = $subscriptionData['status'];
            $subscription->modified_at = $subscriptionData['updated_time'];
            $subscription->zoho_customer_id = $subscriptionData['customer_id'];
            $subscription->save();

            if (isset($subscriptionData['addons'])) {
                foreach ($subscriptionData['addons'] as $addon) {
                    if ($video = Video::where('zoho_addon_code', $addon['addon_code'])->first()) {
                        $customer->videos()->attach($video->id);
                    }
                }
            }

            DB::commit();

            return $subscription;
        }  catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            return new Response(
                'Hook processing error! Data is not in sync, if next hook executed successfully then data in would be in sync!',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getClientSubscription(Request $request)
    {
        $activeSubscription = Subscription::whereHas('customer', function ($query) use ($request) {
            return $query->where('installation_id', '=', $request->header('InstallationId'))
                ->whereIn('subscription_status', Subscription::ACTIVE_STATUSES);
        })->first();

        return $activeSubscription ?? Subscription::whereHas('customer', function ($query) use ($request) {
                return $query->where('installation_id', '=', $request->header('InstallationId'))
                    ->whereNotIn('subscription_status', Subscription::ACTIVE_STATUSES);
            })->first();

    }

}