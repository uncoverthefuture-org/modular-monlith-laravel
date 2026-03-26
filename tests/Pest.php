<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;

uses(TestCase::class);

beforeEach(function () {
    // Bind app to container if not already bound
    if (!app()->bound('app')) {
        $app = new Container();
        Container::setInstance($app);
        
        $app->bind('app', fn () => $app);
        $app->bind('config', fn () => new \Illuminate\Config\Repository([
            'modular' => [
                'paths' => [
                    'controller' => base_path('app/Http/Controllers'),
                    'model' => base_path('app/Models'),
                    'validation' => base_path('app/Validations'),
                    'middleware' => base_path('app/Http/Middleware'),
                    'service' => base_path('app/Services'),
                    'observer' => base_path('app/Observers'),
                    'migration' => database_path('migrations'),
                ],
                'namespaces' => [
                    'controller' => 'App\\Http\\Controllers',
                    'model' => 'App\\Models',
                    'validation' => 'App\\Validations',
                    'middleware' => 'App\\Http\\Middleware',
                    'service' => 'App\\Services',
                    'observer' => 'App\\Observers',
                ],
                'base_classes' => [
                    'controller' => 'Uncover\\ModularMonolith\\Http\\Controllers\\ModularController',
                    'model' => 'Uncover\\ModularMonolith\\Models\\ModularModel',
                    'validation' => 'Uncover\\ModularMonolith\\Validations\\ModularValidation',
                    'middleware' => 'Uncover\\ModularMonolith\\Middleware\\ModularMiddleware',
                    'service' => 'Uncover\\ModularMonolith\\Services\\ModularService',
                    'observer' => 'Uncover\\ModularMonolith\\Observers\\ModularObserver',
                ],
                'routes' => [
                    'middleware' => ['api'],
                    'use_plural' => true,
                    'separator' => '-',
                ],
            ],
        ]));
    }
});
