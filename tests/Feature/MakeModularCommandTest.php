<?php

use Illuminate\Support\Facades\File;
use Uncover\ModularMonolith\Console\Commands\MakeModularCommand;

beforeEach(function () {
    $this->tempDir = sys_get_temp_dir() . '/modular-test-' . uniqid();
    mkdir($this->tempDir . '/app/Http/Controllers', 0777, true);
    mkdir($this->tempDir . '/app/Models', 0777, true);
    mkdir($this->tempDir . '/app/Validations', 0777, true);
    mkdir($this->tempDir . '/app/Http/Middleware', 0777, true);
    mkdir($this->tempDir . '/app/Services', 0777, true);
    mkdir($this->tempDir . '/app/Observers', 0777, true);
    mkdir($this->tempDir . '/database/migrations', 0777, true);
    
    // Mock the app path
    app()->bind('path', fn () => $this->tempDir);
    app()->bind('path.models', fn () => $this->tempDir . '/app/Models');
    app()->bind('path.config', fn () => $this->tempDir);
    app()->bind('path.database', fn () => $this->tempDir . '/database');
});

afterEach(function () {
    // Clean up temp directory
    if (is_dir($this->tempDir)) {
        exec('rm -rf ' . $this->tempDir);
    }
});

test('command can generate controller stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('controller');
    
    expect($stub)->toContain('{{name}}Controller');
    expect($stub)->toContain('extends {{baseControllerName}}');
    expect($stub)->toContain('protected static string $model');
});

test('command can generate model stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('model');
    
    expect($stub)->toContain('class {{name}} extends {{baseModelName}}');
    expect($stub)->toContain("protected \$table = '{{table}}'");
});

test('command can generate validation stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('validation');
    
    expect($stub)->toContain('class {{name}}Validation extends {{baseValidationName}}');
    expect($stub)->toContain('createRules');
    expect($stub)->toContain('queryRules');
});

test('command can generate middleware stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('middleware');
    
    expect($stub)->toContain('class {{name}}Middleware extends {{baseMiddlewareName}}');
});

test('command can generate service stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('service');
    
    expect($stub)->toContain('class {{name}}Service extends {{baseServiceName}}');
    expect($stub)->toContain('protected static string $model');
});

test('command can generate observer stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('observer');
    
    expect($stub)->toContain('class {{name}}Observer extends {{baseObserverName}}');
    expect($stub)->toContain('getObservedModel');
});

test('command can generate migration stub content', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    
    $stub = $command->getStub('migration');
    
    expect($stub)->toContain("Schema::create('{{table}}'");
    expect($stub)->toContain('$table->uuid');
});