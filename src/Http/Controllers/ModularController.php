<?php

namespace Uncover\ModularMonolith\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait JsonApiResponse
{
    protected static function successResponse(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected static function clientErrResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected static function serverErrResponse(string $message, int $code = 500, \Exception $exception = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (config('app.debug') && $exception !== null) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        }

        return response()->json($response, $code);
    }

    protected static function paginatedResponse($data, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
        ]);
    }
}

abstract class ModularController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, JsonApiResponse;

    /**
     * The model class this controller manages.
     * Must be defined in the extending class.
     */
    protected static string $model = '';

    /**
     * The validation class this controller uses.
     * Must be defined in the extending class.
     */
    protected static string $validation = '';

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
     * Get the validation instance.
     */
    protected static function getValidation(): string
    {
        if (empty(static::$validation)) {
            throw new \RuntimeException('Validation class not defined in ' . static::class);
        }

        return static::$validation;
    }

    /**
     * Create one or multiple records.
     * Handles both single and batch creation.
     */
    public function create(Request $request)
    {
        try {
            $data = $request->all();

            // Handle batch creation
            if (isset($data['items']) && is_array($data['items'])) {
                return $this->createBatch($data['items']);
            }

            // Single creation
            $validator = $this->getValidation()::validationRules($request, 'create');

            if ($validator->fails()) {
                return self::clientErrResponse('Validation failed', 422, $validator->errors());
            }

            $model = $this->getModel();
            $record = $model::create($data);

            return self::successResponse(__('modular.created'), $record, 201);
        } catch (ValidationException $e) {
            return self::clientErrResponse($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            return self::serverErrResponse(__('modular.create_failed'), 500, $e);
        }
    }

    /**
     * Create multiple records in batch.
     */
    protected function createBatch(array $items)
    {
        try {
            $created = [];
            $errors = [];

            foreach ($items as $index => $itemData) {
                $validator = $this->getValidation()::validationRules(request()->replace($itemData), 'create');

                if ($validator->fails()) {
                    $errors[$index] = $validator->errors();
                    continue;
                }

                $model = $this->getModel();
                $created[] = $model::create($itemData);
            }

            if (!empty($errors)) {
                return self::clientErrResponse('Some items failed validation', 422, $errors);
            }

            return self::successResponse(__('modular.created_batch'), $created, 201);
        } catch (\Exception $e) {
            return self::serverErrResponse(__('modular.create_batch_failed'), 500, $e);
        }
    }

    /**
     * Read a single record by UUID.
     */
    public function read(string $id)
    {
        try {
            $model = $this->getModel();
            $record = $model::where('uuid', $id)->first();

            if (!$record) {
                return self::clientErrResponse(__('modular.not_found'), 404);
            }

            return self::successResponse(__('modular.read'), $record);
        } catch (\Exception $e) {
            return self::serverErrResponse(__('modular.read_failed'), 500, $e);
        }
    }

    /**
     * Update a single record.
     */
    public function update(Request $request, string $id)
    {
        try {
            $model = $this->getModel();
            $record = $model::where('uuid', $id)->first();

            if (!$record) {
                return self::clientErrResponse(__('modular.not_found'), 404);
            }

            // Normalize request to include the ID for validation
            $request->merge(['id' => $id]);

            $validator = $this->getValidation()::validationRules($request, 'update');

            if ($validator->fails()) {
                return self::clientErrResponse('Validation failed', 422, $validator->errors());
            }

            $record->update($request->all());

            return self::successResponse(__('modular.updated'), $record);
        } catch (ValidationException $e) {
            return self::clientErrResponse($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            return self::serverErrResponse(__('modular.update_failed'), 500, $e);
        }
    }

    /**
     * Delete one or multiple records.
     * Supports single UUID or array of UUIDs for batch delete.
     */
    public function delete(Request $request, ?string $id = null)
    {
        try {
            // Normalize the delete operation
            $ids = $this->normalizeDeleteIds($request, $id);

            if (empty($ids)) {
                return self::clientErrResponse(__('modular.no_ids_provided'), 400);
            }

            $model = $this->getModel();
            $deleted = [];
            $notFound = [];

            foreach ($ids as $recordId) {
                $record = $model::where('uuid', $recordId)->first();

                if (!$record) {
                    $notFound[] = $recordId;
                    continue;
                }

                $record->delete();
                $deleted[] = $recordId;
            }

            return self::successResponse(__('modular.deleted'), [
                'deleted' => $deleted,
                'not_found' => $notFound,
            ]);
        } catch (\Exception $e) {
            return self::serverErrResponse(__('modular.delete_failed'), 500, $e);
        }
    }

    /**
     * Normalize delete IDs from various input formats.
     */
    protected function normalizeDeleteIds(Request $request, ?string $id): array
    {
        $ids = [];

        // Route parameter takes precedence
        if ($id !== null) {
            $ids[] = $id;
        }

        // Check for 'ids' array in request
        if ($request->has('ids')) {
            $requestIds = $request->input('ids');
            if (is_array($requestIds)) {
                $ids = array_merge($ids, $requestIds);
            } elseif (is_string($requestIds)) {
                // Handle comma-separated IDs
                $ids = array_merge($ids, explode(',', $requestIds));
            }
        }

        // Check for 'id' parameter
        if ($request->has('id') && $id === null) {
            $requestId = $request->input('id');
            if (is_array($requestId)) {
                $ids = array_merge($ids, $requestId);
            } else {
                $ids[] = $requestId;
            }
        }

        return array_unique(array_filter($ids));
    }

    /**
     * Query records with filtering, sorting, and pagination.
     */
    public function query(Request $request)
    {
        try {
            $validator = $this->getValidation()::validationRules($request, 'query');

            if ($validator->fails()) {
                return self::clientErrResponse('Validation failed', 422, $validator->errors());
            }

            $model = $this->getModel();
            $query = $model::query();

            // Apply filters
            $query = $this->applyFilters($query, $request);

            // Apply sorting
            $query = $this->applySorting($query, $request);

            // Apply includes/eager loading
            $query = $this->applyIncludes($query, $request);

            // Apply pagination
            $perPage = $request->input('per_page', 15);
            $results = $query->paginate($perPage);

            return self::paginatedResponse($results, __('modular.query_success'));
        } catch (ValidationException $e) {
            return self::clientErrResponse($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            return self::serverErrResponse(__('modular.query_failed'), 500, $e);
        }
    }

    /**
     * Apply filters to the query.
     * Override this method in child classes for custom filtering.
     */
    protected function applyFilters($query, Request $request)
    {
        // Base implementation - can be overridden
        // Filters can be passed as 'filter[key]=value'
        if ($request->has('filter')) {
            $filters = $request->input('filter');
            if (is_array($filters)) {
                foreach ($filters as $key => $value) {
                    if (is_array($value)) {
                        $query->whereIn($key, $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        // Search support
        if ($request->has('search') && $request->has('search_fields')) {
            $search = $request->input('search');
            $fields = $request->input('search_fields');

            if (is_string($fields)) {
                $fields = explode(',', $fields);
            }

            $query->where(function ($q) use ($fields, $search) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            });
        }

        return $query;
    }

    /**
     * Apply sorting to the query.
     */
    protected function applySorting($query, Request $request)
    {
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Validate sort order
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Apply includes/eager loading to the query.
     */
    protected function applyIncludes($query, Request $request)
    {
        if ($request->has('include')) {
            $includes = $request->input('include');
            if (is_string($includes)) {
                $includes = explode(',', $includes);
            }
            $query->with($includes);
        }

        return $query;
    }
}
