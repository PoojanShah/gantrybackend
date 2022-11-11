<?php


namespace App\Repositories;


use App\DTO\Addon;
use App\DTO\DataTransferObject;
use App\Services\DTONormalizer;
use GuzzleHttp\Command\Result;
use Illuminate\Support\Collection;
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

    public function buyOneTimeAddons(
        \App\Models\Subscription $subscription,
        array $addZohoAddonCodes,
    ): bool {
// logic for recurring addons
//        if ($removeZohoAddonCodes) {
//            foreach ($subscription->addons as $k => $addon) {
//                if (in_array($addon->addon_code, $removeZohoAddonCodes, true)) {
//                    unset($subscription->addons[$k]);
//                }
//            }
//        }
//
//        foreach ($this->getAddonsByPlanCode($subscription->plan->plan_code) as $addon) {
//            if (in_array($addon->addon_code, $addZohoAddonCodes, true)) {
//                $subscription->addons[] = $addon;
//            }
//        }
//        $subscription = (array)$subscription;
//        foreach ($subscription['addons'] as $k => $addon){
//            $subscription['addons'][$k] = (array)$addon;
//        }

        /** @var Result $response */
        $response = $this->apiClient->addSubscriptionOnetimeAddon(
            [
                'subscription_id' => $subscription->zoho_subscription_id,
                'addons' => array_map(
                    function ($value) {
                        return [
                            'addon_code' => $value,
                            'quantity' => 1,
                        ];
                    },
                    $addZohoAddonCodes
                ),
            ]
        )->toArray();

        return (int)$response['code'] === 0;
    }

    public function getOne(string $addonCode): DataTransferObject
    {
        return $this->normalizer->denormalize($this->apiClient->getAddon(['addon_code' => $addonCode])
            ->toArray()['addon'], Addon::class);
    }

    public function getAddonsByPlanCode(string $planCode, string $status = 'ACTIVE'): Collection
    {
        $response = $this->apiClient->getPlanAddons([
                'plan_code' => $planCode,
                'filter_by' => 'AddonStatus.' . $status
            ])
            ->toArray();

        if (isset($response['addons'])) {
            foreach ($response['addons'] as $subscriptionParams) {
                $this->collection->add($this->normalizer->denormalize($subscriptionParams, Addon::class));
            }
        }

        return $this->collection;
    }
}