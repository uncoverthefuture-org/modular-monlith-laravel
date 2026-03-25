<?php

namespace Uncover\ModularMonolith\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class ModularValidation
{
    /**
     * Get validation rules based on action.
     *
     * @param Request $request
     * @param string $key The action key (create, read, update, delete, query)
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validationRules(Request $request, string $key)
    {
        $rules = match ($key) {
            'create' => static::createRules($request),
            'read' => static::readRules($request),
            'update' => static::updateRules($request),
            'delete' => static::deleteRules($request),
            'query' => static::queryRules($request),
            default => [],
        };

        return Validator::make($request->all(), $rules);
    }

    /**
     * Rules for creating a record.
     * Override in child class.
     */
    protected static function createRules(Request $request): array
    {
        return [];
    }

    /**
     * Rules for reading a record.
     * Override in child class.
     */
    protected static function readRules(Request $request): array
    {
        return [
            'id' => ['required', 'string', 'uuid'],
        ];
    }

    /**
     * Rules for updating a record.
     * Override in child class.
     */
    protected static function updateRules(Request $request): array
    {
        return [];
    }

    /**
     * Rules for deleting records.
     * Override in child class.
     */
    protected static function deleteRules(Request $request): array
    {
        return [];
    }

    /**
     * Rules for querying records.
     * Override in child class.
     */
    protected static function queryRules(Request $request): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['sometimes', 'string'],
            'sort_order' => ['sometimes', 'string', 'in:asc,desc'],
            'filter' => ['sometimes', 'array'],
            'search' => ['sometimes', 'string'],
            'search_fields' => ['sometimes', 'string'],
            'include' => ['sometimes', 'string'],
        ];
    }

    /**
     * Add custom validation messages.
     * Override in child class.
     */
    protected static function messages(): array
    {
        return [];
    }

    /**
     * Add custom validation attributes.
     * Override in child class.
     */
    protected static function attributes(): array
    {
        return [];
    }
}
