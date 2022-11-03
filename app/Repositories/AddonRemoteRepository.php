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
        array $addZohoAddonCodes = [],
        array $removeZohoAddonCodes = [],
    ): Subscription {

        if ($removeZohoAddonCodes) {
            foreach ($subscription->addons as $k => $addon) {
                if (in_array($addon->addon_code, $removeZohoAddonCodes, true)) {
                    unset($subscription->addons[$k]);
                }
            }
        }

        if ($addZohoAddonCodes) {
            foreach ($this->getAddonsByPlanCode($subscription->plan->plan_code) as $addon) {
                if (in_array($addon->addon_code, $addZohoAddonCodes, true)) {
                    $subscription->addons[] = $addon;
                }
            }
        }
        $subscription = (array)$subscription;
        foreach ($subscription['addons'] as $k => $addon){
            $subscription['addons'][$k] = (array)$addon;
        }

        return $this->normalizer->denormalize($this->apiClient->updateSubscription(
            [
                'subscription_id' => $subscription['subscription_id'],
                'parameters' => ['addons' =>  $subscription['addons']]
            ]
        )
            ->toArray()['subscription'], Subscription::class);
    }

    public function getOne(string $addonCode): DataTransferObject
    {
        return $this->normalizer->denormalize($this->apiClient->getAddon(['addon_code' => $addonCode])
            ->toArray()['addon'], Addon::class);
    }

    public function getAddonsByPlanCode(string $planCode): Collection
    {
        $response = $this->apiClient->getPlanAddons(['plan_code' => $planCode])->toArray();

        if (isset($response['addons'])) {
            foreach ($response['addons'] as $subscriptionParams) {
                $this->collection->add($this->normalizer->denormalize($subscriptionParams, Addon::class));
            }
        }

        return $this->collection;
    }

}