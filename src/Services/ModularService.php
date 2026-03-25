<?php

namespace Uncover\ModularMonolith\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

abstract class ModularService
{
    /**
     * The model class this service manages.
     * Must be defined in the extending class.
     */
    protected static string $model = '';

    /**
     * Cache duration in seconds (null = no cache).
     */
    protected static ?int $cacheDuration = null;

    /**
     * Cache tag for this service.
     */
    protected static ?string $cacheTag = null;

    /**
     * Get the model instance.
     */
    protected static function getModel(): string
    {
        if (empty(static::$model)) {
            throw new \RuntimeException('Model not defined in ' . static::class);
        }

        return static::$model;
    }

    /**
     * Find a record by UUID.
     */
    public static function find(string $uuid): ?Model
    {
        $cacheKey = static::getCacheKey("find:{$uuid}");
        
        if (static::shouldCache()) {
            return Cache::tags(static::getCacheTags())
                ->remember($cacheKey, static::$cacheDuration, function () use ($uuid) {
                    return static::getModel()::where('uuid', $uuid)->first();
                });
        }

        return static::getModel()::where('uuid', $uuid)->first();
    }

    /**
     * Create a new record.
     */
    public static function create(array $data): Model
    {
        $model = static::getModel();
        
        return DB::transaction(function () use ($model, $data) {
            $record = $model::create($data);
            static::clearCache();
            return $record;
        });
    }

    /**
     * Update a record.
     */
    public static function update(string $uuid, array $data): ?Model
    {
        return DB::transaction(function () use ($uuid, $data) {
            $record = static::find($uuid);
            
            if (!$record) {
                return null;
            }

            $record->update($data);
            static::clearCache("find:{$uuid}");
            
            return $record->fresh();
        });
    }

    /**
     * Delete a record.
     */
    public static function delete(string $uuid): bool
    {
        return DB::transaction(function () use ($uuid) {
            $record = static::find($uuid);
            
            if (!$record) {
                return false;
            }

            $deleted = $record->delete();
            static::clearCache("find:{$uuid}");
            
            return $deleted;
        });
    }

    /**
     * Query records with filters.
     */
    public static function query(array $filters = [], array $options = [])
    {
        $query = static::getModel()::query();

        // Apply custom filters
        $query = static::applyFilters($query, $filters);

        // Apply includes
        if (!empty($options['include'])) {
            $query->with($options['include']);
        }

        // Apply sorting
        $sortBy = $options['sort_by'] ?? 'created_at';
        $sortOrder = $options['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $options['per_page'] ?? 15;
        
        return $query->paginate($perPage);
    }

    /**
     * Apply filters to query.
     * Override in child class for custom filtering.
     */
    protected static function applyFilters($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        return $query;
    }

    /**
     * Execute within a transaction.
     */
    public static function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * Check if caching is enabled.
     */
    protected static function shouldCache(): bool
    {
        return static::$cacheDuration !== null && static::$cacheDuration > 0;
    }

    /**
     * Get cache key.
     */
    protected static function getCacheKey(string $suffix): string
    {
        $tag = static::$cacheTag ?? class_basename(static::class);
        return "{$tag}:{$suffix}";
    }

    /**
     * Get cache tags.
     */
    protected static function getCacheTags(): array
    {
        return [static::$cacheTag ?? class_basename(static::class)];
    }

    /**
     * Clear cache.
     */
    protected static function clearCache(?string $key = null): void
    {
        if (!static::shouldCache()) {
            return;
        }

        if ($key) {
            Cache::tags(static::getCacheTags())->forget(static::getCacheKey($key));
        } else {
            Cache::tags(static::getCacheTags())->flush();
        }
    }

    /**
     * Get model class name.
     */
    public static function getModelClass(): string
    {
        return static::$model;
    }
}
