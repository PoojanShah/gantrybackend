<?php


namespace App\Services;

use App\Interfaces\OAuthClientInterface;
use App\Interfaces\SubscriptionsApiClientInterface;
use SudiptoChoudhury\Zoho\Subscriptions\Api;
use Weble\ZohoClient\OAuthClient;

class SubscriptionsApiClient extends Api implements SubscriptionsApiClientInterface
{
    public function __construct(OAuthClientInterface $authClient, $ordId)
    {
//        dd($authClient->getAccessToken(), $authClient->getRefreshToken());
        parent::__construct(
                [
                    'oauthtoken' => $authClient->getAccessToken(),
                    'zohoOrgId' => $ordId
                ]
        );
    }

}