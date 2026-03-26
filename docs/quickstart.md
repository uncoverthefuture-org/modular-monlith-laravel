# Quick Start

## Generate a Module

```bash
# Generate controller and model (default)
php artisan modular:make EmailVerification

# Generate all components
php artisan modular:make EmailVerification --all

# Generate specific components
php artisan modular:make EmailVerification --validation --middleware --service
```

This creates:
- `app/Http/Controllers/EmailVerificationController.php`
- `app/Models/EmailVerification.php`
- `app/Validations/EmailVerificationValidation.php`
- `app/Http/Middleware/EmailVerificationMiddleware.php`
- `app/Services/EmailVerificationService.php`
- `app/Observers/EmailVerificationObserver.php`

## Register Routes

In `routes/api.php`:

```php
use App\Http\Controllers\EmailVerificationController;

Route::moduleResource('email-verifications', EmailVerificationController::class);
```

## Generated Files

### Controller

```php
class EmailVerificationController extends ModularController
{
    protected static string $model = EmailVerification::class;
    protected static string $validation = EmailVerificationValidation::class;
}
```

### Model

```php
class EmailVerification extends ModularModel
{
    protected $table = 'email_verifications';
    protected $fillable = ['uuid', 'email', 'verified_at'];
}
```

All files extend the base classes from the package.
