<?php

use Uncover\ModularMonolith\Validations\ModularValidation;

test('modular validation has validation rules method', function () {
    expect(method_exists(ModularValidation::class, 'validationRules'))->toBeTrue();
});

test('modular validation has action methods', function () {
    expect(method_exists(ModularValidation::class, 'createRules'))->toBeTrue();
    expect(method_exists(ModularValidation::class, 'readRules'))->toBeTrue();
    expect(method_exists(ModularValidation::class, 'updateRules'))->toBeTrue();
    expect(method_exists(ModularValidation::class, 'deleteRules'))->toBeTrue();
    expect(method_exists(ModularValidation::class, 'queryRules'))->toBeTrue();
});
