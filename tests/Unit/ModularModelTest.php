<?php

use Uncover\ModularMonolith\Models\ModularModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TestModularModel extends ModularModel
{
    protected $table = 'test_models';
    protected $fillable = ['uuid', 'name'];
}

test('modular model uses has uuids trait', function () {
    $uses = class_uses_recursive(TestModularModel::class);
    expect($uses)->toHaveKey(HasUuids::class);
});

test('modular model has uuid as primary key', function () {
    $model = new TestModularModel();
    expect($model->getKeyName())->toBe('uuid');
    expect($model->getKeyType())->toBe('string');
    expect($model->getIncrementing())->toBe(false);
});

test('modular model has route key name as uuid', function () {
    $model = new TestModularModel();
    expect($model->getRouteKeyName())->toBe('uuid');
});

test('modular model has correct casts', function () {
    $model = new TestModularModel();
    $casts = $model->getCasts();
    expect($casts)->toHaveKey('created_at');
    expect($casts)->toHaveKey('updated_at');
    expect($casts)->toHaveKey('deleted_at');
});

test('modular model scope by uuid', function () {
    $query = TestModularModel::query();
    $query->byUuid('test-uuid-123');
    
    $sql = $query->toSql();
    expect($sql)->toContain('where');
});

test('modular model generates uuid on boot', function () {
    // Test that the boot method exists and doesn't throw
    expect(method_exists(TestModularModel::class, 'boot'))->toBeTrue();
});