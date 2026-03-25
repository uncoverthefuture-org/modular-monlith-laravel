# Contributing to modular-monolith-laravel

Thank you for considering contributing to this package!

## How to Contribute

1. **Fork** the repository
2. **Clone** your fork: `git clone https://github.com/YOUR_USERNAME/modular-monolith-laravel.git`
3. **Create** a feature branch: `git checkout -b feature/my-new-feature`
4. **Install** dependencies: `composer install`
5. **Make** your changes
6. **Run** tests: `composer test`
7. **Check** code style: `composer cs:check`
8. **Run** static analysis: `composer stan`
9. **Commit** your changes: `git commit -m "Add some feature"`
10. **Push** to your fork: `git push origin feature/my-new-feature`
11. **Create** a Pull Request

## Coding Standards

This package uses:
- **PHP-CS-Fixer** for code style (PSR-12)
- **PHPStan** for static analysis

Run `composer cs` to fix style issues automatically.

## Testing

All tests must pass before PRs can be merged. Run tests with:

```bash
composer test
```

## Bug Reports

Please use GitHub Issues to report bugs. Include:
- Description of the issue
- Steps to reproduce
- Expected behavior
- Actual behavior

## Feature Requests

Open an issue to discuss new features before implementing.