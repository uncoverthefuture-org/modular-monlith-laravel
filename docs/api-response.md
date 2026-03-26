# API Response Format

## Success Responses

### Single Record

```json
{
  "status": "success",
  "message": "Record created",
  "data": {
    "uuid": "...",
    "email": "test@example.com"
  }
}
```

### Paginated List

```json
{
  "status": "success",
  "message": "Success",
  "data": [
    { "uuid": "...", "email": "test1@example.com" },
    { "uuid": "...", "email": "test2@example.com" }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 73
  }
}
```

## Error Responses

### Validation Error

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Generic Error

```json
{
  "status": "error",
  "message": "Record not found"
}
```
