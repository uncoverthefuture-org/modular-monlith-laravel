# Changelog

All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.1](https://github.com/uncoverthefuture-org/modular-monlith-laravel/compare/v1.1.0...v1.1.1) (2026-03-26)


### Bug Fixes

* add workflow_dispatch and fetch all secrets from Infisical ([7fe5822](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/7fe5822d19847288c7e17f241fdf1cd3962d34a9))
* update Packagist workflow to trigger on release-please PR merge ([71097b9](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/71097b9396bd70278c0ea4b3f0c56cc2d6696d1f))
* use JSON body format and trigger on PR merge ([153cdcc](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/153cdccbb74c6d473a33ac0fe6e26f9f9eb7b0d1))

## [1.1.0](https://github.com/uncoverthefuture-org/modular-monlith-laravel/compare/v1.0.0...v1.1.0) (2026-03-26)


### Features

* add Packagist update workflow ([e42c767](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/e42c7678aa90cdf4a0a182f78c234442dff0b571))
* add Packagist workflow and GitHub files ([7be50bf](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/7be50bfcf0e33bed3d1bb688c6a5b5bf4cd4ad9e))
* add Packagist workflow and GitHub files ([859c608](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/859c60861b788d216e7e13a242e42d2c648dd6d2))


### Bug Fixes

* correct Infisical secrets-action workflow parameters ([1fedde6](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/1fedde6b1de4495b4159d9087c8020c5fbfa90aa))
* update Infisical workflow with required params ([00c6c94](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/00c6c94de493388945d3cf1549d543de162c97ad))
* update Packagist workflow to include environment variables ([7a7ac89](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/7a7ac898c719081f03cb7ba840d3606e8c2bc176))
* use specific version v1.0.15 for Infisical action ([f5d7393](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/f5d739304c9a1e297b776ae6eb96ab3105b9b7a1))

## 1.0.0 (2026-03-26)


### Bug Fixes

* remove composer.lock and add platform config ([6852105](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/6852105eedea41b6ccd80ecb9a5bbbb473c7ccfe))
* resolve test suite and composer issues ([b0b4615](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/b0b4615684e178d39253e30c123cfb4ac599e2b6))
* resolve test suite and composer issues ([cc3cf05](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/cc3cf05a5bd633094ba4709d15167de31b4fb1e9))
* update release-please action and disable main branch tests ([4b25ce2](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/4b25ce2f1df92b0d858e2814197eff40049abc2a))
* update release-please action and disable main branch tests ([7043fb3](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/7043fb3c96e9badc92ba4ce6a6a76d3d189bb357))
* update workflow to use shivammathur/setup-php v2 ([9615ec2](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/9615ec21103f26c3710426c7f96b4dd5a9dc1d2a))
* use composer update for CI compatibility ([4ad810b](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/4ad810bdec80dac8357c80e7d4c58b365b1987e2))
* use PHP 8.2+ for Pest v2 compatibility ([0957ecd](https://github.com/uncoverthefuture-org/modular-monlith-laravel/commit/0957ecdd089f1c1b1dd0b436f9449a077364ba86))

## [Unreleased]

## [1.0.0] - YYYY-MM-DD

### Added
- Initial release of modular-monolith-laravel package
- One command module generation (`modular:make`)
- Base ModularController with full CRUD operations
- Base ModularModel with UUID support
- Base ModularValidation with action-based rules
- Base ModularMiddleware with per-action authorization
- Base ModularService with caching and CRUD
- Base ModularObserver for model lifecycle events
- Route macro (`Route::moduleResource`) for REST endpoints
- Batch operations support
- Publishable base classes
- Customizable paths and namespaces
- PHPUnit/Pest test suite
- GitHub Actions CI/CD workflows
- Release-please for semantic versioning

---

## Versioning Guide

### Semantic Versioning (SemVer) Rules

Given a version number `MAJOR.MINOR.PATCH`:

1. **MAJOR** (`X.0.0`) - Incompatible API changes
2. **MINOR** (`1.X.0`) - New backward-compatible functionality
3. **PATCH** (`1.0.X`) - Backward-compatible bug fixes

### Commit Message Convention

We use [Conventional Commits](https://www.conventionalcommits.org/) for clear changelog generation:

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

**Types:**
- `feat:` - New feature (MINOR bump)
- `fix:` - Bug fix (PATCH bump)
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting, no logic)
- `refactor:` - Code refactoring
- `perf:` - Performance improvements
- `test:` - Test additions/changes
- `chore:` - Maintenance tasks
- `BREAKING CHANGE:` - Breaking change (MAJOR bump)

**Examples:**
```
feat(controller): add batch create support
fix(middleware): resolve authorization issue
docs: update installation instructions
refactor: simplify validation logic
fix!: remove deprecated method (MAJOR)
```

### Release Workflow

1. **Development**: Work on features/fixes
2. **Push**: Commits trigger release-please on main
3. **Release PR**: release-please creates a PR with version bump
4. **Merge**: Merging the release PR creates the GitHub release
5. **Packagist**: Auto-updates via webhook

### Version Constraints in composer.json

For Laravel packages, use caret (`^`) for backward compatibility:

```json
"require": {
    "php": "^8.0",
    "illuminate/support": "^9.0|^10.0|^11.0"
}
```

This allows:
- `^9.0` - Compatible with 9.x, 10.x, 11.x
- Patch updates are always safe
