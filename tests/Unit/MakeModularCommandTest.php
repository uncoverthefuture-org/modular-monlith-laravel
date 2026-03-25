<?php

use Uncover\ModularMonolith\Console\Commands\MakeModularCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->fs = new Filesystem();
    $this->app = app();
});

test('make modular command exists', function () {
    expect(class_exists(MakeModularCommand::class))->toBeTrue();
});

test('make modular command has correct signature', function () {
    $command = new MakeModularCommand($this->fs);
    expect($command->getName())->toBe('modular:make');
});

test('make modular command validates studly case name', function () {
    $command = new MakeModularCommand($this->fs);
    
    expect($command->isValidName('EmailVerification'))->toBeTrue();
    expect($command->isValidName('User'))->toBeTrue();
    expect($command->isValidName('UserProfile'))->toBeTrue();
    expect($command->isValidName('emailverification'))->toBeFalse();
    expect($command->isValidName('email_verification'))->toBeFalse();
    expect($command->isValidName('123User'))->toBeFalse();
});

test('make modular command generates table name correctly', function () {
    $command = new MakeModularCommand($this->fs);
    
    expect($command->getTableName('User'))->toBe('users');
    expect($command->getTableName('EmailVerification'))->toBe('email_verifications');
    expect($command->getTableName('UserProfile'))->toBe('user_profiles');
});

test('make modular command generates migration file name correctly', function () {
    $command = new MakeModularCommand($this->fs);
    
    // Just check it doesn't throw
    expect(fn () => $command->getMigrationFileName('User'))->not->toThrow();
});

test('make modular command stub replacement works', function () {
    $command = new MakeModularCommand($this->fs);
    
    $stub = 'namespace {{namespace}}; class {{name}} extends {{base}}';
    $replacements = [
        '{{namespace}}' => 'App\Http\Controllers',
        '{{name}}' => 'TestController',
        '{{base}}' => 'Controller',
    ];
    
    $result = $command->populateStub($stub, $replacements);
    
    expect($result)->toBe('namespace App\Http\Controllers; class TestController extends Controller');
});