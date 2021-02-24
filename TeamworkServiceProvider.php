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
        $this->app->singleton(RawEndpoints\Desk\Tickets::class, function () use ($baseUrl, $tokenDesk) {
            $rawTickets = new RawEndpoints\Desk\Tickets(app(HttpClient::class));
            return $rawTickets->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Timelogs::class, function () use ($baseUrl, $tokenDesk) {
            $rawTimelogs = new RawEndpoints\Desk\Timelogs(app(HttpClient::class));
            return $rawTimelogs->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Users::class, function () use ($baseUrl, $tokenDesk) {
            $rawUsers = new RawEndpoints\Desk\Users(app(HttpClient::class));
            return $rawUsers->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Customers::class, function () use ($baseUrl, $tokenDesk) {
            $rawCustomers = new RawEndpoints\Desk\Customers(app(HttpClient::class));
            return $rawCustomers->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Companies::class, function () use ($baseUrl, $tokenDesk) {
            $rawCompanies = new RawEndpoints\Desk\Companies(app(HttpClient::class));
            return $rawCompanies->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Contacts::class, function () use ($baseUrl, $tokenDesk) {
            $rawContacts = new RawEndpoints\Desk\Contacts(app(HttpClient::class));
            return $rawContacts->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\Inboxes::class, function () use ($baseUrl, $tokenDesk) {
            $rawInboxes = new RawEndpoints\Desk\Inboxes(app(HttpClient::class));
            return $rawInboxes->setBaseUrl($baseUrl)->setToken($tokenDesk);
        });

        $this->app->singleton(RawEndpoints\Desk\CustomFields::class, function () use ($baseUrl, $tokenDesk) {
            $rawCustomFields = new RawEndpoints\Desk\CustomFields(app(HttpClient::class));
            return $rawCustomFields->setBaseUrl($baseUrl)->setToken($tokenDesk);
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

        $tokenProjects = config('teamwork.projects.token');
        $routerProjects = new Endpoints\Router();

        //$routerProjects->registerEndpoint($this->app->make(Endpoints\Desk\Users::class));

        $this->app->singleton(RawEndpoints\Projects\Timelogs::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawTimelogs = new RawEndpoints\Projects\Timelogs(app(HttpClient::class));
                return $rawTimelogs->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\Tasks::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawTimelogs = new RawEndpoints\Projects\Tasks(app(HttpClient::class));
                return $rawTimelogs->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\Teams::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawTeams = new RawEndpoints\Projects\Teams(app(HttpClient::class));
                return $rawTeams->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\People::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawPeople = new RawEndpoints\Projects\People(app(HttpClient::class));
                return $rawPeople->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\Tags::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawTags = new RawEndpoints\Projects\Tags(app(HttpClient::class));
                return $rawTags->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\Companies::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawCompanies = new RawEndpoints\Projects\Companies(app(HttpClient::class));
                return $rawCompanies->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\Projects::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawProjects = new RawEndpoints\Projects\Projects(app(HttpClient::class));
                return $rawProjects->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });

        $this->app->singleton(RawEndpoints\Projects\TodoLists::class,
            function () use ($baseUrl, $tokenProjects) {
                $rawTodoLists = new RawEndpoints\Projects\TodoLists(app(HttpClient::class));
                return $rawTodoLists->setBaseUrl($baseUrl)->setToken($tokenProjects);
            });


        $this->app->singleton(Endpoints\Projects\Timelogs::class, function () use ($routerProjects) {
            $rawTimelogs = app(RawEndpoints\Projects\Timelogs::class);
            return new Endpoints\Projects\Timelogs($rawTimelogs, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\Tasks::class, function () use ($routerProjects) {
            $rawTasks = app(RawEndpoints\Projects\Tasks::class);
            return new Endpoints\Projects\Tasks($rawTasks, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\Teams::class, function () use ($routerProjects) {
            $rawTeams = app(RawEndpoints\Projects\Teams::class);
            return new Endpoints\Projects\Teams($rawTeams, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\People::class, function () use ($routerProjects) {
            $rawPeople = app(RawEndpoints\Projects\People::class);
            return new Endpoints\Projects\People($rawPeople, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\Tags::class, function () use ($routerProjects) {
            $rawTags = app(RawEndpoints\Projects\Tags::class);
            return new Endpoints\Projects\Tags($rawTags, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\Companies::class, function () use ($routerProjects) {
            $rawCompanies = app(RawEndpoints\Projects\Companies::class);
            return new Endpoints\Projects\Companies($rawCompanies, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\Projects::class, function () use ($routerProjects) {
            $rawProjects = app(RawEndpoints\Projects\Projects::class);
            return new Endpoints\Projects\Projects($rawProjects, $routerProjects);
        });

        $this->app->singleton(Endpoints\Projects\TodoLists::class, function () use ($routerProjects) {
            $rawTodoLists = app(RawEndpoints\Projects\TodoLists::class);
            return new Endpoints\Projects\TodoLists($rawTodoLists, $routerProjects);
        });

        $this->app->make(Endpoints\Projects\People::class);
        $this->app->make(Endpoints\Projects\Teams::class);
        $this->app->make(Endpoints\Projects\Tasks::class);
        $this->app->make(Endpoints\Projects\Timelogs::class);
        $this->app->make(Endpoints\Projects\Tags::class);
        $this->app->make(Endpoints\Projects\Companies::class);
        $this->app->make(Endpoints\Projects\Projects::class);
        $this->app->make(Endpoints\Projects\TodoLists::class);

    }
}
