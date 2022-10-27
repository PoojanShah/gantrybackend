<?php


namespace App\Services;

use App\Interfaces\OAuthClientInterface;
use App\Models\Settings;
use Weble\ZohoClient\Enums\Region;
use Weble\ZohoClient\OAuthClient as ZohoOAuthClient;

class OAuthClient extends ZohoOAuthClient implements OAuthClientInterface
{
    public function __construct(string $clientId, string $clientSecret, string $region = Region::US, string $redirectUri = '')
    {
        if(Settings::getGlobalRefreshToken()){
            $this->setRefreshToken(Settings::getGlobalRefreshToken()); // refresh token doesnt expire - so use it as infinite token - any other approaches require access approval from zoho user
        }

        parent::__construct($clientId, $clientSecret, $region, $redirectUri);
    }
}