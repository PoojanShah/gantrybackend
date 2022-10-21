<?php

namespace App\Providers;

use App\Interfaces\OAuthClientInterface;
use App\Interfaces\SubscriptionsApiClientInterface;
use App\Services\OAuthClient;
use App\Services\SubscriptionsApiClient;
use Illuminate\Support\ServiceProvider;
use Psr\Cache\CacheItemPoolInterface;
use Illuminate\Contracts\Cache\Repository;
use Weble\ZohoClient\Enums\Region;

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
                env('ZOHO_API_CLIENT_ID_OFFLINE'),
                env('ZOHO_API_CLIENT_SECRET_OFFLINE'),
                Region::US,

            );
            $client->promptForConsent(false);// Optional setting: Prompts for user consent each time your app tries to access user credentials.
            $client->setScopes(['ZohoSubscriptions.fullaccess.all']); // Set the zoho
            $client->setRefreshToken(env('ZOHO_API_REFRESH_TOKEN')); // refresh token doenst expire - so use it as infinite token - any other approaches require access approval from zoho user
//            $client->useCache(new CacheItemPool($app->make(Repository::class)));//  use einar-hansen/laravel-psr-6-cache

            return $client;
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
        $this->app->bind(
            SubscriptionsApiClientInterface::class ,
            fn () => $this->app->make(SubscriptionsApiClient::class)
);
        $this->app->singleton(SubscriptionsApiClientInterface::class, function ($app) {
            return new SubscriptionsApiClient(
                 $app->make(OAuthClientInterface::class),
                env('ZOHO_ORG_ID')
            );
        }
        );
    }
}
