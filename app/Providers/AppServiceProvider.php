<?php

namespace App\Providers;

use App\Interfaces\OAuthClientInterface;
use App\Models\Settings;
use App\Services\OAuthClient;
use EinarHansen\Cache\CacheItemPool;
use Illuminate\Support\ServiceProvider;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use Psr\Cache\CacheItemPoolInterface;
use Illuminate\Contracts\Cache\Repository;
use SudiptoChoudhury\Support\Forge\Api\Client;
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

        $this->app->bind(DocBlockFactoryInterface::class, function () {
            return DocBlockFactory::createInstance();
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
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        } else {
            \URL::forceScheme('http');

        }
    }
}
