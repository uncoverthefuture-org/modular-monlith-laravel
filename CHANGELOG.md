# Changelog

All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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