# Laravel Modular Monolith Package

<p align="center">
  <a href="https://packagist.org/packages/uncover/modular-monlith-laravel">
    <img src="https://img.shields.io/packagist/v/uncover/modular-monlith-laravel.svg" alt="Latest Version">
  </a>
  <a href="https://packagist.org/packages/uncover/modular-monlith-laravel">
    <img src="https://img.shields.io/packagist/dt/uncover/modular-monlith-laravel.svg" alt="Total Downloads">
  </a>
  <a href="https://github.com/uncoverthefuture-org/modular-monlith-laravel/actions">
    <img src="https://github.com/uncoverthefuture-org/modular-monlith-laravel/actions/workflows/tests.yml/badge.svg" alt="Tests">
  </a>
</p>

A Laravel package that provides scaffolding for building modular monolith applications with standardized CRUD patterns, UUID support, and automatic route registration.

## Features

- **One Command Module Generation** - Generate Controller, Model, Validation, Middleware, Service, and Observer with a single artisan command
- **Base Classes with Full CRUD** - Pre-built base classes with complete CRUD operations
- **Route Macro** - `Route::moduleResource()` for automatic REST endpoint registration
- **UUID Support** - Automatic UUID generation and handling
- **Batch Operations** - Built-in support for batch create and delete
- **Flexible Validation** - Per-action validation rules
- **Service Layer** - Base service with caching and query methods
- **Observers** - Base observer for model lifecycle events

## Requirements

- PHP 8.2+
- Laravel 9.0, 10.0, or 11.0

## Quick Start

```bash
# Install the package
composer require uncover/modular-monlith-laravel

# Generate a module
php artisan modular:make EmailVerification

# Add routes
Route::moduleResource('email-verifications', EmailVerificationController::class);
```

That's it! Your CRUD endpoints are ready.

## License

The MIT License (MIT). See [License File](https://github.com/uncoverthefuture-org/modular-monlith-laravel/blob/main/LICENSE) for more information.
