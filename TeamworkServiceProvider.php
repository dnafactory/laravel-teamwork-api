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

        $this->app['router']->post(config('teamwork.webhook_url'), WebhookController::class . '@index');
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

        $this->app->singleton(RawEndpoints\Desk\CustomFields::class, function () use ($baseUrlDesk, $tokenDesk) {
            $rawCustomFields = new RawEndpoints\Desk\CustomFields(app(HttpClient::class));
            return $rawCustomFields->setBaseUrl($baseUrlDesk)->setToken($tokenDesk);
        });

        $this->app->singleton(Endpoints\Router::class);

        $this->app->singleton(Endpoints\Desk\Tickets::class);
        $this->app->singleton(Endpoints\Desk\Timelogs::class);
        $this->app->singleton(Endpoints\Desk\Users::class);
        $this->app->singleton(Endpoints\Desk\Customers::class);
        $this->app->singleton(Endpoints\Desk\Companies::class);
        $this->app->singleton(Endpoints\Desk\Inboxes::class);
        $this->app->singleton(Endpoints\Desk\CustomFields::class);
        $this->app->singleton(Endpoints\Desk\CustomFieldOptions::class);
        $this->app->singleton(Endpoints\Projects\Timelogs::class);

        $this->app->make(Endpoints\Desk\Tickets::class);
        $this->app->make(Endpoints\Desk\Timelogs::class);
        $this->app->make(Endpoints\Desk\Users::class);
        $this->app->make(Endpoints\Desk\Customers::class);
        $this->app->make(Endpoints\Desk\Companies::class);
        $this->app->make(Endpoints\Desk\Inboxes::class);
        $this->app->make(Endpoints\Desk\CustomFields::class);
        $this->app->make(Endpoints\Desk\CustomFieldOptions::class);


        $baseUrlProjects = $baseUrl . '/projects/api';
        $tokenProjects = config('teamwork.projects.token');
        $routerProjects = new Endpoints\Router();

        $routerProjects->registerEndpoint($this->app->make(Endpoints\Desk\Users::class));

        $this->app->singleton(RawEndpoints\Projects\Timelogs::class,
            function () use ($baseUrlProjects, $tokenProjects) {
                $rawTimelogs = new RawEndpoints\Projects\Timelogs(app(HttpClient::class));
                return $rawTimelogs->setBaseUrl($baseUrlProjects)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\Tasks::class,
            function () use ($baseUrlProjects, $tokenProjects) {
                $rawTimelogs = new RawEndpoints\Projects\Tasks(app(HttpClient::class));
                return $rawTimelogs->setBaseUrl($baseUrlProjects)->setToken($tokenProjects);
            });

        $this->app->singleton(Endpoints\Projects\Timelogs::class, function () use ($routerProjects) {
            $rawTimelogs = app(RawEndpoints\Projects\Timelogs::class);
            return new Endpoints\Projects\Timelogs($rawTimelogs, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\Tasks::class, function () use ($routerProjects) {
            $rawTasks = app(RawEndpoints\Projects\Tasks::class);
            return new Endpoints\Projects\Tasks($rawTasks, $routerProjects);
        });


    }
}