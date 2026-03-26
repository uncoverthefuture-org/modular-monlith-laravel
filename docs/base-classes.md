# Base Classes

## ModularController

Full CRUD methods:

- `create(Request $request)` - Create single or batch records
- `read(string $id)` - Get single record by UUID
- `update(Request $request, string $id)` - Update record
- `delete(Request $request, ?string $id)` - Delete single or batch
- `query(Request $request)` - List with filtering, sorting, pagination

### Query Parameters

- **Pagination**: `?per_page=25`
- **Sorting**: `?sort_by=created_at&sort_order=desc`
- **Filtering**: `?filter[status]=active`
- **Search**: `?search=john&search_fields=name,email`
- **Eager Loading**: `?include=author,comments`

## ModularModel

- UUID primary key
- HasUuids trait
- Soft deletes
- Route key by UUID

## ModularValidation

Action-based rules:

```php
protected static function createRules(Request $request): array
{
    return ['email' => ['required', 'email']];
}

protected static function updateRules(Request $request): array
{
    return ['email' => ['sometimes', 'email']];
}
```

## ModularMiddleware

Per-action authorization:

```php
protected array $actionMethodMap = [
    'create' => 'authorizeCreate',
    'read' => 'authorizeRead',
    'update' => 'authorizeUpdate',
    'delete' => 'authorizeDelete',
    'query' => 'authorizeQuery',
];
```

## ModularService

- Caching support
- Transaction support
- Query methods

```php
protected static ?int $cacheDuration = 3600;
```

## ModularObserver

Model lifecycle events:

- `created`
- `updated`
- `deleted`
- `retrieved`
- etc.

Register in service provider:

```php
EmailVerificationObserver::register();
```
