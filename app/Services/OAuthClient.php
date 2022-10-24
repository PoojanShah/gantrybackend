<?php


namespace App\Services;

use App\Interfaces\OAuthClientInterface;
use Weble\ZohoClient\OAuthClient as ZohoOAuthClient;

class OAuthClient extends ZohoOAuthClient implements OAuthClientInterface
{
}