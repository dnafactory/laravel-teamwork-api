<?php

namespace DNAFactory\Teamwork;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class TeamworkServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/config/teamwork.php' => config_path('teamwork.php'),
            ], 'config');
        }

        $this->app['router']->post(config('teamwork.webhook_url'), WebhookController::class.'@index');
    }

    public function register()
    {

        $baseUrl = config('teamwork.base_url');
        $tokenDesk = config('teamwork.desk.token');

        // raw endpoints
        $baseUrlDesk = $baseUrl . '/desk/api';

        $this->app->singleton(RawEndpoints\Desk\Tickets::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawTickets = new RawEndpoints\Desk\Tickets(app(HttpClient::class));
            return $rawTickets->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Timelogs::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawTimelogs = new RawEndpoints\Desk\Timelogs(app(HttpClient::class));
            return $rawTimelogs->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Users::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawUsers = new RawEndpoints\Desk\Users(app(HttpClient::class));
            return $rawUsers->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Customers::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawCustomers = new RawEndpoints\Desk\Customers(app(HttpClient::class));
            return $rawCustomers->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Companies::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawCompanies = new RawEndpoints\Desk\Companies(app(HttpClient::class));
            return $rawCompanies->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Contacts::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawContacts = new RawEndpoints\Desk\Contacts(app(HttpClient::class));
            return $rawContacts->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Inboxes::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawInboxes = new RawEndpoints\Desk\Inboxes(app(HttpClient::class));
            return $rawInboxes->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(Endpoints\Router::class, function () {
            return new Endpoints\Router();
        });
        
        $this->app->singleton(Endpoints\Desk\Tickets::class);
        $this->app->singleton(Endpoints\Desk\Timelogs::class);
        $this->app->singleton(Endpoints\Desk\Users::class);
        $this->app->singleton(Endpoints\Desk\Customers::class);
        $this->app->singleton(Endpoints\Desk\Companies::class);
        $this->app->singleton(Endpoints\Desk\Inboxes::class);
    }
}