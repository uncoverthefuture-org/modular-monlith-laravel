<?php

use Uncover\ModularMonolith\Console\Commands\MakeModularCommand;

test('make modular command exists', function () {
    expect(class_exists(MakeModularCommand::class))->toBeTrue();
});

test('make modular command has correct signature', function () {
    $command = new MakeModularCommand(new \Illuminate\Filesystem\Filesystem);
    expect($command->getName())->toBe('modular:make');
});
