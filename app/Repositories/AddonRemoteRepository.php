<?php


namespace App\Repositories;


use App\DTO\Addon;
use App\DTO\DataTransferObject;
use App\DTO\Subscription;
use App\DTO\SubscriptionPreview;
use App\Models\Customer;
use App\Services\DTONormalizer;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use SudiptoChoudhury\Zoho\Subscriptions\Api;

class AddonRemoteRepository
{
    private Api $apiClient;
    private DTONormalizer $normalizer;
    private Collection $collection;

    public function __construct(Api $apiClient, DTONormalizer $normalizer, Collection $collection)
    {
        $this->apiClient = $apiClient;
        $this->normalizer = $normalizer;
        $this->collection = $collection;
    }

    public function addRemoveSubscriptionAddon(
        Subscription $subscription,
        string $planCode,
        array $addZohoAddonCodes = [],
        array $removeZohoAddonCodes = [],
    ): Subscription {

        foreach ($subscription->addons as $k => $addon){
            if (in_array($addon->addon_code, $removeZohoAddonCodes, true)){
                unset($subscription->addons[$k]);
            }
        }

        foreach ($this->getAddonsByPlanCode($planCode) as $addon){
            if(in_array($addon->addon_code, $addZohoAddonCodes, true)){
                $subscription->addons[] = $addon;
            }
        }

        return $this->normalizer->denormalize($this->apiClient->updateSubscription((array) $subscription)
            ->toArray()['subscription'], Subscription::class);

    }

    public function getAddonsByPlanCode(string $planCode): Collection
    {
        $response = $this->apiClient->getPlanAddons(['plan_code' => $planCode])->toArray();

        dd($response);

        if(isset($response['addons'])){
            foreach ($response['addons'] as $subscriptionParams){
                $this->collection->add($this->normalizer->denormalize($subscriptionParams, Addon::class));
            }
        }

        return $this->collection;
    }

}