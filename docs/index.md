# Introduction

Welcome to the Laravel Modular Monolith Package documentation.

This package provides scaffolding for building modular monolith Laravel applications with standardized CRUD patterns, UUID support, and automatic route registration.

## Installation

```bash
composer require uncover/modular-monolith-laravel
```

## Quick Start

### Generate a Module

```bash
# Generate controller and model (default)
php artisan modular:make EmailVerification

# Generate all components
php artisan modular:make EmailVerification --all
```

### Add Routes

```php
use App\Http\Controllers\EmailVerificationController;

Route::moduleResource('email-verifications', EmailVerificationController::class);
```

This automatically creates:
- `POST /email-verifications` → create
- `GET /email-verifications` → query
- `GET /email-verifications/{id}` → read
- `PATCH /email-verifications/{id}` → update
- `DELETE /email-verifications/{id}` → delete
