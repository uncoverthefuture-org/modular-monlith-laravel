# Installation

## Requirements

- PHP 8.2+
- Laravel 9.0, 10.0, or 11.0

## Install Package

```bash
composer require uncover/modular-monlith-laravel
```

The package auto-discovers and registers its service provider.

## Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=modular-config
```

This creates `config/modular.php` where you can customize paths and namespaces.

## Publish Base Classes (Optional)

```bash
php artisan vendor:publish --tag=modular-base-classes
```

This lets you customize the base classes.
