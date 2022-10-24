<?php

namespace App\Providers;

use App\Interfaces\OAuthClientInterface;
use App\Services\OAuthClient;
use EinarHansen\Cache\CacheItemPool;
use Illuminate\Support\ServiceProvider;
use Psr\Cache\CacheItemPoolInterface;
use Illuminate\Contracts\Cache\Repository;
use SudiptoChoudhury\Zoho\Subscriptions\Api;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OAuthClientInterface::class, function ($app) {
            $client = new OAuthClient(
                config('zoho.ZOHO_API_CLIENT_ID_OFFLINE'),
                config('zoho.ZOHO_API_CLIENT_SECRET_OFFLINE'),
                config('zoho.ZOHO_API_REGION'),
                config('zoho.ZOHO_API_REDIRECT_URI_OFFLINE'),
            );
            $client->promptForConsent(false);// Optional setting: Prompts for user consent each time your app tries to access user credentials.
            $client->setScopes(['ZohoSubscriptions.fullaccess.all']); // Set the zoho
//            dd($client->getAuthorizationUrl(['ZohoSubscriptions.fullaccess.all']));
            $client->setRefreshToken(config('zoho.ZOHO_API_REFRESH_TOKEN')); // refresh token doenst expire - so use it as infinite token - any other approaches require access approval from zoho user
            $client->useCache(new CacheItemPool($app->make(Repository::class)));//  use einar-hansen/laravel-psr-6-cache

            return $client;
        }
        );

        $this->app->bind(Api::class, function ($app) {
            return new Api(
                [
                    'oauthtoken' => $app->make(OAuthClientInterface::class)->getAccessToken(),
                    'zohoOrgId' => config('zoho.ZOHO_ORG_ID'),
                    'client' => ['headers' => ['Authorization' => 'Zoho-oauthtoken {{oauthtoken}}']],
                ]
            );
        }
        );

        $this->app->singleton(CacheItemPoolInterface::class, function ($app) {
            return new CacheItemPool($app->make(Repository::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
