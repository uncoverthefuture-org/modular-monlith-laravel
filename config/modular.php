<?php

return [
    'paths' => [
        'controller' => app_path('Http/Controllers'),
        'model' => app_path('Models'),
        'validation' => app_path('Validations'),
        'middleware' => app_path('Http/Middleware'),
        'service' => app_path('Services'),
        'observer' => app_path('Observers'),
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
        'controller' => \Uncover\ModularMonolith\Http\Controllers\ModularController::class,
        'model' => \Uncover\ModularMonolith\Models\ModularModel::class,
        'validation' => \Uncover\ModularMonolith\Validations\ModularValidation::class,
        'middleware' => \Uncover\ModularMonolith\Middleware\ModularMiddleware::class,
        'service' => \Uncover\ModularMonolith\Services\ModularService::class,
        'observer' => \Uncover\ModularMonolith\Observers\ModularObserver::class,
    ],

    'routes' => [
        'middleware' => ['api'],
        'use_plural' => true,
        'separator' => '-',
    ],

    'uuid' => [
        'column' => 'uuid',
        'auto_generate' => true,
    ],
];
