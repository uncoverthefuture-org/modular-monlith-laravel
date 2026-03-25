<?php

use Uncover\ModularMonolith\Validations\ModularValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestValidation extends ModularValidation
{
    protected static function createRules(Request $request): array
    {
        return ['name' => ['required', 'string', 'max:255']];
    }

    protected static function updateRules(Request $request): array
    {
        return ['name' => ['sometimes', 'string', 'max:255']];
    }

    protected static function queryRules(Request $request): array
    {
        return ['per_page' => ['sometimes', 'integer', 'min:1', 'max:100']];
    }
}

test('modular validation has validation rules method', function () {
    expect(method_exists(TestValidation::class, 'validationRules'))->toBeTrue();
});

test('modular validation returns validator for create action', function () {
    $request = new Request(['name' => 'Test']);
    $validator = TestValidation::validationRules($request, 'create');
    
    expect($validator)->toBeInstanceOf(\Illuminate\Contracts\Validation\Validator::class);
    expect($validator->passes())->toBeTrue();
});

test('modular validation fails for missing required fields on create', function () {
    $request = new Request([]);
    $validator = TestValidation::validationRules($request, 'create');
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
});

test('modular validation returns validator for update action', function () {
    $request = new Request(['name' => 'Test']);
    $validator = TestValidation::validationRules($request, 'update');
    
    expect($validator)->toBeInstanceOf(\Illuminate\Contracts\Validation\Validator::class);
});

test('modular validation returns validator for query action', function () {
    $request = new Request(['per_page' => 25]);
    $validator = TestValidation::validationRules($request, 'query');
    
    expect($validator)->toBeInstanceOf(\Illuminate\Contracts\Validation\Validator::class);
    expect($validator->passes())->toBeTrue();
});

test('modular validation handles invalid query params', function () {
    $request = new Request(['per_page' => 'invalid']);
    $validator = TestValidation::validationRules($request, 'query');
    
    expect($validator->fails())->toBeTrue();
});

test('modular validation returns empty rules for unknown action', function () {
    $request = new Request([]);
    $validator = TestValidation::validationRules($request, 'unknown_action');
    
    expect($validator->rules())->toBeEmpty();
});

test('modular validation has read rules for uuid validation', function () {
    $request = new Request(['id' => 'invalid-uuid']);
    $validator = TestValidation::validationRules($request, 'read');
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('id'))->toBeTrue();
});

test('modular validation passes with valid uuid on read', function () {
    $request = new Request(['id' => '550e8400-e29b-41d4-a716-446655440000']);
    $validator = TestValidation::validationRules($request, 'read');
    
    expect($validator->passes())->toBeTrue();
});