<?php


namespace App\Services;

use App\Interfaces\OAuthClientInterface;
use EinarHansen\Cache\CacheItemPool;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Weble\ZohoClient\Enums\Region;
use Weble\ZohoClient\Exception\AccessTokenNotSet;
use Weble\ZohoClient\OAuthClient as ZohoOAuthClient;

class OAuthClient extends ZohoOAuthClient implements OAuthClientInterface
{

//  public function getAccessToken(): string
//  {
//      $token = '';
//      try {
//          $token = parent::getAccessToken();
//      } catch (AccessTokenNotSet $exception) {
//          //TODO insecure...use host from env
//          $_SESSION['zoho_oauth_beginning_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//          $url = $this->getAuthorizationUrl();
//          $_SESSION['zoho_oauth_state'] = $this->getState();// Get the state for security, and save it (usually in session)
//          redirect($url); // Do your redirection as you prefer, redirect to controller
//          dd($url);
//      }
//dd($token);
//      return $token;
//  }
}