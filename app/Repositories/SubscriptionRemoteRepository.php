<?php

namespace App\Repositories;


use App\DTO\DataTransferObject;
use App\DTO\Subscription;
use App\DTO\SubscriptionPreview;
use App\Models\Customer;
use App\Services\DTONormalizer;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use SudiptoChoudhury\Zoho\Subscriptions\Api;

class SubscriptionRemoteRepository
{
    public const ALL = 'All';
    public const LIVE = 'LIVE';

    private Api $apiClient;
    private DTONormalizer $normalizer;
    private Collection $collection;

    public function __construct(Api $apiClient, DTONormalizer $normalizer, Collection $collection)
    {
        $this->apiClient = $apiClient;
        $this->normalizer = $normalizer;
        $this->collection = $collection;
    }

    public function getOne(string $id): DataTransferObject
    {
        return $this->normalizer->denormalize($this->apiClient->getSubscription(['subscription_id' => $id])
            ->toArray()['subscription'], Subscription::class);
    }

    public function getSubscriptionsByCustomer(Customer $customer, string $status = self::ALL): Collection
    {
        $response = $this->apiClient->getCustomersSubscriptions([
            'customer_id' => $customer->zoho_customer_id,
            'filter_by' => 'SubscriptionStatus.' . $status
        ])
            ->toArray();

        if (isset($response['subscriptions'])) {
            foreach ($response['subscriptions'] as $subscriptionParams) {
                $this->collection->add($this->normalizer->denormalize($subscriptionParams, SubscriptionPreview::class));
            }
        }

        return $this->collection;
    }

}