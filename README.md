# Laravel Modular Monolith Package

<p align="center">
    <a href="https://packagist.org/packages/uncover/modular-monolith-laravel"><img src="https://img.shields.io/packagist/v/uncover/modular-monolith-laravel.svg" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/uncover/modular-monolith-laravel"><img src="https://img.shields.io/packagist/dt/uncover/modular-monolith-laravel.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/uncover/modular-monolith-laravel"><img src="https://img.shields.io/packagist/l/uncover/modular-monolith-laravel.svg" alt="License"></a>
    <a href="https://github.com/uncoverthefuture-org/modular-monlith-laravel/actions"><img src="https://github.com/uncoverthefuture-org/modular-monlith-laravel/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
</p>

A Laravel package that provides scaffolding for building modular monolith applications with standardized CRUD patterns, UUID support, and automatic route registration.

## Features

- **One Command Module Generation**: Generate Controller, Model, Validation, Middleware, Service, and Observer with a single artisan command
- **Base Classes with Full CRUD**: Pre-built base classes with complete CRUD operations (create, read, update, delete, query)
- **Route Macro**: `Route::moduleResource()` for automatic REST endpoint registration
- **UUID Support**: Automatic UUID generation and handling
- **Batch Operations**: Built-in support for batch create and delete
- **Flexible Validation**: Per-action validation rules (create, read, update, delete, query)
- **Service Layer**: Base service with caching, transaction support, and query methods
- **Observers**: Base observer for model lifecycle events
- **Publishable Base Classes**: Customize base classes if needed

## Requirements

- PHP 8.0+
- Laravel 9.0, 10.0, or 11.0

## Installation

```bash
composer require uncover/modular-monolith-laravel
```

The package will auto-register its service provider.

## Quick Start

### 1. Generate a Module

```bash
# Generate controller and model (default)
php artisan modular:make EmailVerification

# Generate all components
php artisan modular:make EmailVerification --all

# Generate with specific components
php artisan modular:make EmailVerification --validation --middleware --service --observer --migration
```

This creates:
- `app/Http/Controllers/EmailVerificationController.php` - Extends `ModularController`
- `app/Models/EmailVerification.php` - Extends `ModularModel`
- `app/Validations/EmailVerificationValidation.php` - Extends `ModularValidation`
- `app/Http/Middleware/EmailVerificationMiddleware.php` - Extends `ModularMiddleware`
- `app/Services/EmailVerificationService.php` - Extends `ModularService`
- `app/Observers/EmailVerificationObserver.php` - Extends `ModularObserver`
- `database/migrations/xxxx_xx_xx_create_email_verifications_table.php` (with --migration)

### 2. Add Routes

In your `routes/api.php`:

```php
use App\Http\Controllers\EmailVerificationController;

// Basic usage
Route::moduleResource('email-verifications', EmailVerificationController::class);

// With middleware
Route::moduleResource('email-verifications', EmailVerificationController::class, [
    'middleware' => ['auth:sanctum', 'EmailVerificationMiddleware']
]);
```

The `moduleResource` macro automatically creates:
- `POST /email-verifications` → `create`
- `GET /email-verifications` → `query`
- `GET /email-verifications/{id}` → `read`
- `PATCH /email-verifications/{id}` → `update`
- `DELETE /email-verifications/{id?}` → `delete`

## Command Options

| Option | Description |
|--------|-------------|
| `--controller` | Generate a controller (default: true) |
| `--model` | Generate a model (default: true) |
| `--validation` | Generate a validation class |
| `--middleware` | Generate a middleware class |
| `--service` | Generate a service class |
| `--observer` | Generate an observer class |
| `--migration` | Create a migration file |
| `--all` | Generate all components |
| `--force` | Overwrite existing files |

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=modular-config
```

### Available Options

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
    'use_plural' => true,      // email-verifications vs email-verification
    'separator' => '-',        // email-verifications vs emailVerifications
],
```

## Publishing Base Classes

If you want to customize the base classes:

```bash
php artisan vendor:publish --tag=modular-base-classes
```

This publishes all base classes to your app, allowing you to override and customize them.

## Base Classes

### ModularController

The base controller provides these methods:

- `create(Request $request)` - Create single or batch records
- `read(string $id)` - Get a single record by UUID
- `update(Request $request, string $id)` - Update a record
- `delete(Request $request, ?string $id = null)` - Delete single or batch
- `query(Request $request)` - List with filtering, sorting, pagination

#### Query Parameters

The `query` method supports:
- **Pagination**: `?per_page=25`
- **Sorting**: `?sort_by=created_at&sort_order=desc`
- **Filtering**: `?filter[status]=active`
- **Search**: `?search=john&search_fields=name,email`
- **Eager Loading**: `?include=author,comments`

#### Generated Controller

```php
class EmailVerificationController extends ModularController
{
    protected static string $model = EmailVerification::class;
    protected static string $validation = EmailVerificationValidation::class;
    
    // Add custom methods if needed
    public function customMethod(Request $request)
    {
        // Your custom logic
    }
}
```

### ModularModel

The base model includes:
- UUID primary key (`uuid` column)
- HasUuids trait for automatic UUID generation
- Soft deletes support
- Route key by UUID

#### Generated Model

```php
class EmailVerification extends ModularModel
{
    protected $table = 'email_verifications';
    
    protected $fillable = [
        'uuid',
        // Add your fillable attributes here
    ];
}
```

### ModularValidation

Base validation class with action-based rules:

```php
class EmailVerificationValidation extends ModularValidation
{
    protected static function createRules(Request $request): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
    
    protected static function updateRules(Request $request): array
    {
        return [
            'email' => ['sometimes', 'email'],
        ];
    }
}
```

### ModularMiddleware

Base middleware with per-action authorization:

```php
class EmailVerificationMiddleware extends ModularMiddleware
{
    protected array $actionMethodMap = [
        'create' => 'authorizeCreate',
        'read' => 'authorizeRead',
        'update' => 'authorizeUpdate',
        'delete' => 'authorizeDelete',
        'query' => 'authorizeQuery',
    ];
    
    protected function authorizeCreate(Request $request): bool
    {
        return auth()->user()->can('email-verification.create');
    }
}
```

### ModularService

Base service with caching and CRUD operations:

```php
class EmailVerificationService extends ModularService
{
    protected static string $model = EmailVerification::class;
    protected static ?int $cacheDuration = 3600; // Enable caching
    
    // Add custom methods
    public static function findByEmail(string $email): ?EmailVerification
    {
        return static::getModel()::where('email', $email)->first();
    }
}
```

### ModularObserver

Base observer for model lifecycle:

```php
class EmailVerificationObserver extends ModularObserver
{
    public static function getObservedModel(): string
    {
        return EmailVerification::class;
    }
    
    public function created(EmailVerification $model): void
    {
        // Handle after creation
    }
}
```

Register in a service provider:

```php
// AppServiceProvider.php
public function boot()
{
    EmailVerificationObserver::register();
}
```

## JSON Response Format

```json
// Success
{
    "status": "success",
    "message": "Record created",
    "data": { ... }
}

// Paginated
{
    "status": "success",
    "message": "Success",
    "data": [ ... ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 73
    }
}

// Error
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

## Custom Stubs

You can customize the generated file templates:

```bash
php artisan vendor:publish --tag=modular-stubs
```

Then edit files in `stubs/modular/` directory.

## Testing

```bash
composer test
```

Or run specific tests:

```bash
./vendor/bin/phpunit tests/Unit/
./vendor/bin/pest tests/Feature/
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

Please see [SECURITY](SECURITY.md) for our security policy.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Uncover](https://uncoverthefuture.org)
- [All Contributors](https://github.com/uncoverthefuture-org/modular-monlith-laravel/graphs/contributors)