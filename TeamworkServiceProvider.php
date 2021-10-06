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
        $tokenProjects = config('teamwork.projects.token');

        // raw endpoints
        $this->app->when(RawEndpoints\Desk\Tickets::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Tickets::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Tickets::class);

        $this->app->when(RawEndpoints\Desk\Timelogs::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Timelogs::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Timelogs::class);

        $this->app->when(RawEndpoints\Desk\Users::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Users::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Users::class);

        $this->app->when(RawEndpoints\Desk\Customers::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Customers::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Customers::class);

        $this->app->when(RawEndpoints\Desk\Companies::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Companies::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Companies::class);

        $this->app->when(RawEndpoints\Desk\Inboxes::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Inboxes::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Inboxes::class);

        $this->app->when(RawEndpoints\Desk\CustomFields::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\CustomFields::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\CustomFields::class);

        $this->app->when(RawEndpoints\Desk\Messages::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Desk\Messages::class)->needs('$token')->give($tokenDesk);
        $this->app->singleton(RawEndpoints\Desk\Messages::class);

        $this->app->when(RawEndpoints\Projects\Timelogs::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\Timelogs::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\Timelogs::class);

        $this->app->when(RawEndpoints\Projects\Tasks::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\Tasks::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\Tasks::class);

        $this->app->when(RawEndpoints\Projects\Teams::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\Teams::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\Teams::class);

        $this->app->when(RawEndpoints\Projects\People::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\People::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\People::class);

        $this->app->when(RawEndpoints\Projects\Tags::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\Tags::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\Tags::class);

        $this->app->when(RawEndpoints\Projects\Companies::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\Companies::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\Companies::class);

        $this->app->when(RawEndpoints\Projects\Projects::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\Projects::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\Projects::class);

        $this->app->when(RawEndpoints\Projects\TodoLists::class)->needs('$baseUrl')->give($baseUrl);
        $this->app->when(RawEndpoints\Projects\TodoLists::class)->needs('$token')->give($tokenProjects);
        $this->app->singleton(RawEndpoints\Projects\TodoLists::class);

        $this->app->singleton(Endpoints\Desk\Tickets::class);
        $this->app->singleton(Endpoints\Desk\Timelogs::class);
        $this->app->singleton(Endpoints\Desk\Users::class);
        $this->app->singleton(Endpoints\Desk\Customers::class);
        $this->app->singleton(Endpoints\Desk\Companies::class);
        $this->app->singleton(Endpoints\Desk\Inboxes::class);
        $this->app->singleton(Endpoints\Desk\CustomFields::class);
        $this->app->singleton(Endpoints\Desk\CustomFieldOptions::class);
        $this->app->singleton(Endpoints\Desk\Messages::class);
        $this->app->singleton(Endpoints\Projects\Timelogs::class);
        $this->app->singleton(Endpoints\Projects\Timelogs::class);
        $this->app->singleton(Endpoints\Projects\Tasks::class);
        $this->app->singleton(Endpoints\Projects\Teams::class);
        $this->app->singleton(Endpoints\Projects\People::class);
        $this->app->singleton(Endpoints\Projects\Tags::class);
        $this->app->singleton(Endpoints\Projects\Companies::class);
        $this->app->singleton(Endpoints\Projects\Projects::class);
        $this->app->singleton(Endpoints\Projects\TodoLists::class);

        $this->app->singleton(Endpoints\Router::class);

        $endpoints = [
            Endpoints\Desk\Tickets::class,
            Endpoints\Desk\Timelogs::class,
            Endpoints\Desk\Users::class,
            Endpoints\Desk\Customers::class,
            Endpoints\Desk\Companies::class,
            Endpoints\Desk\Inboxes::class,
            Endpoints\Desk\CustomFields::class,
            Endpoints\Desk\CustomFieldOptions::class,
            Endpoints\Desk\Messages::class,
            Endpoints\Projects\People::class,
            Endpoints\Projects\Teams::class,
            Endpoints\Projects\Tasks::class,
            Endpoints\Projects\Timelogs::class,
            Endpoints\Projects\Tags::class,
            Endpoints\Projects\Companies::class,
            Endpoints\Projects\Projects::class,
            Endpoints\Projects\TodoLists::class,
        ];

        $router = app(Endpoints\Router::class);
        foreach ($endpoints as $endpoint){
            $router->registerEndpoint(app($endpoint));
        }

    }
}
