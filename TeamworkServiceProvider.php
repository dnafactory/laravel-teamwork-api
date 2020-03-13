<?php

namespace DNAFactory\Teamwork;

use Illuminate\Support\ServiceProvider;

class TeamworkServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/config/teamwork.php' => config_path('teamwork.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->app->singleton(Projects\Clock::class, function ($app) {
            return new Projects\Clock(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });

        $this->app->singleton(Projects\People::class, function ($app) {
            return new Projects\People(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });

        $this->app->singleton(Projects\Projects::class, function ($app) {
            return new Projects\Projects(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });

        $this->app->singleton(Projects\Tags::class, function ($app) {
            return new Projects\Tags(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });

        $this->app->singleton(Projects\TaskLists::class, function ($app) {
            return new Projects\TaskLists(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });

        $this->app->singleton(Projects\Tasks::class, function ($app) {
            return new Projects\Tasks(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });

        $this->app->singleton(Projects\TimeTracking::class, function ($app) {
            return new Projects\TimeTracking(config('teamwork.projects.base_url'), config('teamwork.projects.token'), config('teamwork.projects.delay'));
        });
    }
}