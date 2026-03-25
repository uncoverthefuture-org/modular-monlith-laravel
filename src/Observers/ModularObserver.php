<?php

namespace Uncover\ModularMonolith\Observers;

use Illuminate\Database\Eloquent\Model;

abstract class ModularObserver
{
    /**
     * Handle the model "creating" event.
     */
    public function creating(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "created" event.
     */
    public function created(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "updating" event.
     */
    public function updating(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "saving" event.
     */
    public function saving(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "saved" event.
     */
    public function saved(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "deleting" event.
     */
    public function deleting(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "restoring" event.
     */
    public function restoring(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "restored" event.
     */
    public function restored(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "force deleted" event.
     */
    public function forceDeleted(Model $model): void
    {
        // Override in child class
    }

    /**
     * Handle the model "retrieved" event.
     */
    public function retrieved(Model $model): void
    {
        // Override in child class
    }

    /**
     * Get the model class this observer watches.
     * Override in child class.
     */
    abstract public static function getObservedModel(): string;

    /**
     * Register this observer.
     * Call this in a service provider.
     */
    public static function register(): void
    {
        $model = static::getObservedModel();
        $model::observe(static::class);
    }

    /**
     * Log model activity.
     * Helper method for logging.
     */
    protected function logActivity(Model $model, string $action, array $data = []): void
    {
        $logData = [
            'model' => get_class($model),
            'uuid' => $model->uuid ?? $model->getKey(),
            'action' => $action,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ];

        // You can customize this to use your preferred logging method
        // Example: ActivityLog::create($logData);
        // Or: logger()->info('Model activity', $logData);
    }

    /**
     * Dispatch a job after model event.
     */
    protected function dispatchJob(string $jobClass, Model $model, array $data = []): void
    {
        if (class_exists($jobClass)) {
            dispatch(new $jobClass($model, $data));
        }
    }

    /**
     * Clear related caches.
     */
    protected function clearRelatedCaches(Model $model): void
    {
        // Override in child class to clear specific caches
    }

    /**
     * Get changed attributes.
     */
    protected function getChangedAttributes(Model $model): array
    {
        return $model->getChanges();
    }

    /**
     * Check if specific attributes changed.
     */
    protected function hasChanged(Model $model, string|array $attributes): bool
    {
        if (is_string($attributes)) {
            return $model->isDirty($attributes);
        }

        foreach ($attributes as $attribute) {
            if ($model->isDirty($attribute)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get original value.
     */
    protected function getOriginal(Model $model, string $attribute): mixed
    {
        return $model->getOriginal($attribute);
    }
}
