<?php

namespace Uncover\ModularMonolith\Traits;

trait ModuleMiddlewareTrait
{
    /**
     * Map of actions to their corresponding middleware methods.
     * Override in child class.
     */
    protected array $actionMethodMap = [];

    /**
     * Get the middleware method for a specific action.
     */
    protected function getMiddlewareMethod(string $action): ?string
    {
        return $this->actionMethodMap[$action] ?? null;
    }

    /**
     * Check if an action has a specific middleware method.
     */
    protected function hasMiddlewareMethod(string $action): bool
    {
        return isset($this->actionMethodMap[$action]);
    }

    /**
     * Normalize request parameters for consistency.
     */
    protected function normalizeRequest(\Illuminate\Http\Request $request, ?string $action = null): void
    {
        // Sync route parameters with request data
        $routeParams = $request->route()?->parameters() ?? [];
        
        foreach ($routeParams as $key => $value) {
            if (!$request->has($key)) {
                $request->merge([$key => $value]);
            }
        }

        // Ensure ID is set for read/update/delete actions
        if (in_array($action, ['read', 'update', 'delete']) && !$request->has('id')) {
            $id = $routeParams['id'] ?? $request->route('id');
            if ($id) {
                $request->merge(['id' => $id]);
            }
        }

        // Normalize delete IDs
        if ($action === 'delete') {
            $this->normalizeDeleteIds($request);
        }
    }

    /**
     * Normalize delete IDs from various input formats.
     */
    protected function normalizeDeleteIds(\Illuminate\Http\Request $request): void
    {
        $ids = [];

        // Check for 'ids' array
        if ($request->has('ids')) {
            $requestIds = $request->input('ids');
            if (is_array($requestIds)) {
                $ids = array_merge($ids, $requestIds);
            } elseif (is_string($requestIds)) {
                $ids = array_merge($ids, explode(',', $requestIds));
            }
        }

        // Check for 'id' parameter
        if ($request->has('id')) {
            $requestId = $request->input('id');
            if (is_array($requestId)) {
                $ids = array_merge($ids, $requestId);
            } else {
                $ids[] = $requestId;
            }
        }

        // Store normalized IDs
        $request->merge(['_normalized_ids' => array_unique(array_filter($ids))]);
    }

    /**
     * Get normalized delete IDs.
     */
    protected function getNormalizedIds(\Illuminate\Http\Request $request): array
    {
        return $request->input('_normalized_ids', []);
    }

    /**
     * Handle batch operations.
     * Returns true if request contains batch items.
     */
    protected function isBatchRequest(\Illuminate\Http\Request $request): bool
    {
        return $request->has('items') && is_array($request->input('items'));
    }

    /**
     * Get batch items from request.
     */
    protected function getBatchItems(\Illuminate\Http\Request $request): array
    {
        return $request->input('items', []);
    }
}
