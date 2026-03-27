# Configuration

## Publish Config

```bash
php artisan vendor:publish --tag=modular-config
```

## Config Options

```php
// config/modular.php

// Customize paths
'paths' => [
    'controller' => app_path('Http/Controllers'),
    'model' => app_path('Models'),
    'validation' => app_path('Validations'),
    'middleware' => app_path('Http/Middleware'),
    'service' => app_path('Services'),
    'observer' => app_path('Observers'),
    'migration' => database_path('migrations'),
],

// Customize namespaces
'namespaces' => [
    'controller' => 'App\Http\Controllers',
    'model' => 'App\Models',
    'validation' => 'App\Validations',
    'middleware' => 'App\Http\Middleware',
    'service' => 'App\Services',
    'observer' => 'App\Observers',
],

// Route configuration
'routes' => [
    'middleware' => ['api'],
    'use_plural' => true,
    'separator' => '-',
],
```

## Command Options

| Option | Description |
|--------|-------------|
| `--controller` | Generate controller |
| `--model` | Generate model |
| `--validation` | Generate validation class |
| `--middleware` | Generate middleware class |
| `--service` | Generate service class |
| `--observer` | Generate observer class |
| `--migration` | Create migration |
| `--all` | Generate all components |
| `--force` | Overwrite existing files |
