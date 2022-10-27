<?php


namespace App\Repositories;


use App\DTO\DataTransferObject;
use App\DTO\Subscription;
use App\DTO\SubscriptionPreview;
use App\Models\Customer;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use SudiptoChoudhury\Zoho\Subscriptions\Api;

class SubscriptionRemoteRepository
{
    private Api $apiClient;
    private DocBlockFactoryInterface $blockFactory;
    private Collection $collection;

    public function __construct(Api $apiClient, DocBlockFactoryInterface $blockFactory, Collection $collection)
    {
        $this->apiClient = $apiClient;
        $this->blockFactory = $blockFactory;
        $this->collection = $collection;
    }

    public function getOne(string $id): Subscription
    {
        return new Subscription($this->apiClient->getSubscription(['subscription_id' => $id])
            ->toArray()['subscription'], $this->blockFactory);
    }

    public function getSubscriptionsByCustomer(Customer $customer): Collection
    {
        $response = $this->apiClient->getCustomersSubscriptions(['customer_id' => $customer->zoho_customer_id])->toArray();

        if(isset($response['subscriptions'])){
            foreach ($response['subscriptions'] as $subscriptionParams){
                $this->collection->add(new SubscriptionPreview($subscriptionParams, $this->blockFactory));
            }
        }

        return $this->collection;
    }

}