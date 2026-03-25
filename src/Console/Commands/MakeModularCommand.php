<?php

namespace Uncover\ModularMonolith\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeModularCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modular:make
                            {name : The name of the module (e.g., EmailVerification)}
                            {--controller : Generate a controller (default: true)}
                            {--model : Generate a model (default: true)}
                            {--validation : Generate a validation class}
                            {--middleware : Generate a middleware class}
                            {--service : Generate a service class}
                            {--observer : Generate an observer class}
                            {--migration : Create a migration file for the module}
                            {--all : Generate controller, model, validation, middleware, service, and observer}
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new modular component with optional controller, model, validation, middleware, service, and observer';

    /**
     * The filesystem instance.
     */
    protected Filesystem $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');

        if (!$this->isValidName($name)) {
            $this->error('Module name must be in StudlyCase (e.g., EmailVerification)');
            return 1;
        }

        // Determine which components to generate
        $generateAll = $this->option('all');
        $generateController = $this->option('controller') ?? $generateAll;
        $generateModel = $this->option('model') ?? $generateAll;
        $generateValidation = $this->option('validation') ?? $generateAll;
        $generateMiddleware = $this->option('middleware') ?? $generateAll;
        $generateService = $this->option('service') ?? $generateAll;
        $generateObserver = $this->option('observer') ?? $generateAll;
        $generateMigration = $this->option('migration');

        // If no flags are provided, default to controller and model
        if (!($generateController || $generateModel || $generateValidation || $generateMiddleware || $generateService || $generateObserver || $generateMigration)) {
            $generateController = true;
            $generateModel = true;
        }

        $this->info("Creating modular component: {$name}");
        $this->newLine();

        $created = [];
        $skipped = [];
        $errors = [];

        // Generate Controller
        if ($generateController) {
            $result = $this->generateController($name);
            if ($result === 'created') {
                $created[] = "Controller: {$name}Controller";
            } elseif ($result === 'skipped') {
                $skipped[] = "Controller: {$name}Controller";
            } else {
                $errors[] = "Controller: {$result}";
            }
        }

        // Generate Model
        if ($generateModel) {
            $result = $this->generateModel($name);
            if ($result === 'created') {
                $created[] = "Model: {$name}";
            } elseif ($result === 'skipped') {
                $skipped[] = "Model: {$name}";
            } else {
                $errors[] = "Model: {$result}";
            }
        }

        // Generate Validation
        if ($generateValidation) {
            $result = $this->generateValidation($name);
            if ($result === 'created') {
                $created[] = "Validation: {$name}Validation";
            } elseif ($result === 'skipped') {
                $skipped[] = "Validation: {$name}Validation";
            } else {
                $errors[] = "Validation: {$result}";
            }
        }

        // Generate Middleware
        if ($generateMiddleware) {
            $result = $this->generateMiddleware($name);
            if ($result === 'created') {
                $created[] = "Middleware: {$name}Middleware";
            } elseif ($result === 'skipped') {
                $skipped[] = "Middleware: {$name}Middleware";
            } else {
                $errors[] = "Middleware: {$result}";
            }
        }

        // Generate Service
        if ($generateService) {
            $result = $this->generateService($name);
            if ($result === 'created') {
                $created[] = "Service: {$name}Service";
            } elseif ($result === 'skipped') {
                $skipped[] = "Service: {$name}Service";
            } else {
                $errors[] = "Service: {$result}";
            }
        }

        // Generate Observer
        if ($generateObserver) {
            $result = $this->generateObserver($name);
            if ($result === 'created') {
                $created[] = "Observer: {$name}Observer";
            } elseif ($result === 'skipped') {
                $skipped[] = "Observer: {$name}Observer";
            } else {
                $errors[] = "Observer: {$result}";
            }
        }

        // Generate Migration (if requested)
        if ($generateMigration) {
            $result = $this->generateMigration($name);
            if ($result === 'created') {
                $created[] = "Migration: {$this->getMigrationFileName($name)}";
            } elseif ($result === 'skipped') {
                $skipped[] = "Migration: {$this->getMigrationFileName($name)}";
            } else {
                $errors[] = "Migration: {$result}";
            }
        }

        // Print summary
        if (!empty($created)) {
            $this->info('Created:');
            foreach ($created as $item) {
                $this->line("  ✓ {$item}");
            }
            $this->newLine();
        }

        if (!empty($skipped)) {
            $this->warn('Skipped (already exist):');
            foreach ($skipped as $item) {
                $this->line("  - {$item}");
            }
            $this->newLine();
        }

        if (!empty($errors)) {
            $this->error('Errors:');
            foreach ($errors as $item) {
                $this->line("  ✗ {$item}");
            }
            $this->newLine();
            return 1;
        }

        // Output example route registration
        $this->printRouteExample($name);

        return 0;
    }

    /**
     * Validate the module name.
     */
    protected function isValidName(string $name): bool
    {
        return preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name) === 1;
    }

    /**
     * Generate the controller file.
     */
    protected function generateController(string $name): string
    {
        $path = config('modular.paths.controller', app_path('Http/Controllers'));
        $namespace = config('modular.namespaces.controller', 'App\Http\Controllers');
        $baseController = config('modular.base_classes.controller', 'Uncover\ModularMonolith\Http\Controllers\ModularController');;

        $filePath = $path . "/{$name}Controller.php";

        if (!$this->option('force') && $this->files->exists($filePath)) {
            return 'skipped';
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('controller');
        $content = $this->populateStub($stub, [
            '{{namespace}}' => $namespace,
            '{{baseController}}' => $baseController,
            '{{baseControllerName}}' => 'ModularController',
            '{{name}}' => $name,
            '{{modelName}}' => $name,
            '{{validationName}}' => "{$name}Validation",
            '{{modelNamespace}}' => config('modular.namespaces.model', 'App\Models'),
            '{{validationNamespace}}' => config('modular.namespaces.validation', 'App\Validations'),
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Generate the model file.
     */
    protected function generateModel(string $name): string
    {
        $path = config('modular.paths.model', app_path('Models'));
        $namespace = config('modular.namespaces.model', 'App\Models');
        $baseModel = config('modular.base_classes.model', 'Uncover\ModularMonolith\Models\ModularModel');

        $filePath = $path . "/{$name}.php";

        if (!$this->option('force') && $this->files->exists($filePath)) {
            return 'skipped';
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('model');
        $content = $this->populateStub($stub, [
            '{{namespace}}' => $namespace,
            '{{baseModel}}' => $baseModel,
            '{{baseModelName}}' => 'ModularModel',
            '{{name}}' => $name,
            '{{table}}' => $this->getTableName($name),
            '{{fillable}}' => "'uuid'",
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Generate the validation file.
     */
    protected function generateValidation(string $name): string
    {
        $path = config('modular.paths.validation', app_path('Validations'));
        $namespace = config('modular.namespaces.validation', 'App\Validations');

        $filePath = $path . "/{$name}Validation.php";

        if (!$this->option('force') && $this->files->exists($filePath)) {
            return 'skipped';
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('validation');
        $content = $this->populateStub($stub, [
            '{{namespace}}' => $namespace,
            '{{baseValidation}}' => config('modular.base_classes.validation', 'Uncover\ModularMonolith\Validations\ModularValidation'),
            '{{baseValidationName}}' => 'ModularValidation',
            '{{name}}' => $name,
            '{{table}}' => $this->getTableName($name),
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Generate the middleware file.
     */
    protected function generateMiddleware(string $name): string
    {
        $path = config('modular.paths.middleware', app_path('Http/Middleware'));
        $namespace = config('modular.namespaces.middleware', 'App\Http\Middleware');

        $filePath = $path . "/{$name}Middleware.php";

        if (!$this->option('force') && $this->files->exists($filePath)) {
            return 'skipped';
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('middleware');
        $content = $this->populateStub($stub, [
            '{{namespace}}' => $namespace,
            '{{baseMiddleware}}' => config('modular.base_classes.middleware', 'Uncover\ModularMonolith\Middleware\ModularMiddleware'),
            '{{baseMiddlewareName}}' => 'ModularMiddleware',
            '{{name}}' => $name,
            '{{nameLower}}' => strtolower($name),
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Generate the service file.
     */
    protected function generateService(string $name): string
    {
        $path = config('modular.paths.service', app_path('Services'));
        $namespace = config('modular.namespaces.service', 'App\Services');

        $filePath = $path . "/{$name}Service.php";

        if (!$this->option('force') && $this->files->exists($filePath)) {
            return 'skipped';
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('service');
        $content = $this->populateStub($stub, [
            '{{namespace}}' => $namespace,
            '{{baseService}}' => config('modular.base_classes.service', 'Uncover\ModularMonolith\Services\ModularService'),
            '{{baseServiceName}}' => 'ModularService',
            '{{name}}' => $name,
            '{{nameLower}}' => strtolower($name),
            '{{modelName}}' => $name,
            '{{table}}' => $this->getTableName($name),
            '{{modelNamespace}}' => config('modular.namespaces.model', 'App\Models'),
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Generate the observer file.
     */
    protected function generateObserver(string $name): string
    {
        $path = config('modular.paths.observer', app_path('Observers'));
        $namespace = config('modular.namespaces.observer', 'App\Observers');

        $filePath = $path . "/{$name}Observer.php";

        if (!$this->option('force') && $this->files->exists($filePath)) {
            return 'skipped';
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('observer');
        $content = $this->populateStub($stub, [
            '{{namespace}}' => $namespace,
            '{{baseObserver}}' => config('modular.base_classes.observer', 'Uncover\ModularMonolith\Observers\ModularObserver'),
            '{{baseObserverName}}' => 'ModularObserver',
            '{{name}}' => $name,
            '{{modelName}}' => $name,
            '{{modelNameLower}}' => lcfirst($name),
            '{{modelNamespace}}' => config('modular.namespaces.model', 'App\Models'),
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Generate the migration file.
     */
    protected function generateMigration(string $name): string
    {
        $path = config('modular.paths.migration', database_path('migrations'));
        $tableName = $this->getTableName($name);
        $fileName = $this->getMigrationFileName($name);

        $filePath = $path . "/{$fileName}.php";

        // Check for existing migrations for this table
        if (!$this->option('force')) {
            $existingMigrations = glob($path . "/*_create_{$tableName}_table.php");
            if (!empty($existingMigrations)) {
                return 'skipped';
            }
        }

        $this->makeDirectory($path);

        $stub = $this->getStub('migration');
        $content = $this->populateStub($stub, [
            '{{table}}' => $tableName,
        ]);

        $this->files->put($filePath, $content);

        return 'created';
    }

    /**
     * Get the stub file content.
     */
    protected function getStub(string $type): string
    {
        $customStubPath = base_path("stubs/modular/{$type}.stub");

        if ($this->files->exists($customStubPath)) {
            return $this->files->get($customStubPath);
        }

        $packageStubPath = __DIR__ . "/../../stubs/{$type}.stub";

        if ($this->files->exists($packageStubPath)) {
            return $this->files->get($packageStubPath);
        }

        // Fallback to inline stubs
        return $this->getInlineStub($type);
    }

    /**
     * Populate a stub with replacements.
     */
    protected function populateStub(string $stub, array $replacements): string
    {
        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Create the directory if it doesn't exist.
     */
    protected function makeDirectory(string $path): void
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }
    }

    /**
     * Get the table name from the module name.
     */
    protected function getTableName(string $name): string
    {
        $separator = config('modular.routes.separator', '_');
        return Str::snake(Str::plural($name), $separator);
    }

    /**
     * Get the migration file name.
     */
    protected function getMigrationFileName(string $name): string
    {
        $timestamp = date('Y_m_d_His');
        $tableName = $this->getTableName($name);
        return "{$timestamp}_create_{$tableName}_table";
    }

    /**
     * Print route registration example.
     */
    protected function printRouteExample(string $name): void
    {
        $separator = config('modular.routes.separator', '-');
        $usePlural = config('modular.routes.use_plural', true);

        $routeName = $usePlural ? Str::plural($name) : $name;
        $routeName = Str::kebab($routeName);
        $routeName = str_replace('-', $separator, $routeName);

        $namespace = config('modular.namespaces.controller', 'App\Http\Controllers');
        $controllerClass = "\\{$namespace}\\{$name}Controller";

        $this->info('Add this to your routes file (e.g., routes/api.php):');
        $this->newLine();
        $this->line("use {$controllerClass};");
        $this->newLine();
        $this->line("Route::moduleResource('{$routeName}', {$name}Controller::class);");
        $this->newLine();
        $this->line('Or with middleware:');
        $this->line("Route::moduleResource('{$routeName}', {$name}Controller::class, [");
        $this->line("    'middleware' => ['auth:sanctum', '{$name}Middleware']");
        $this->line(']);');
        $this->newLine();
    }

    /**
     * Get inline stub as fallback.
     */
    protected function getInlineStub(string $type): string
    {
        $stubs = [
            'controller' => '<?php

namespace {{namespace}};

use {{baseController}};
use {{modelNamespace}}\{{modelName}};
use {{validationNamespace}}\{{validationName}};
use Illuminate\Http\Request;

class {{name}}Controller extends {{baseControllerName}}
{
    /**
     * The model class this controller manages.
     */
    protected static string $model = {{modelName}}::class;

    /**
     * The validation class this controller uses.
     */
    protected static string $validation = {{validationName}}::class;
}
',

            'model' => '<?php

namespace {{namespace}};

use {{baseModel}};

class {{name}} extends {{baseModelName}}
{
    /**
     * The table associated with the model.
     */
    protected $table = \'{{table}}\';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        {{fillable}},
        // Add your fillable attributes here
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        // Add hidden attributes here
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        // Add cast attributes here
    ];
}
',

            'validation' => '<?php

namespace {{namespace}};

use {{baseValidation}};
use Illuminate\Http\Request;

class {{name}}Validation extends {{baseValidationName}}
{
    /**
     * Rules for creating a record.
     */
    protected static function createRules(Request $request): array
    {
        return [
            // \'name\' => [\'required\', \'string\', \'max:255\'],
        ];
    }

    /**
     * Rules for reading a record.
     */
    protected static function readRules(Request $request): array
    {
        return [
            \'id\' => [\'required\', \'string\', \'uuid\'],
        ];
    }

    /**
     * Rules for updating a record.
     */
    protected static function updateRules(Request $request): array
    {
        return [
            // \'name\' => [\'sometimes\', \'string\', \'max:255\'],
        ];
    }

    /**
     * Rules for deleting records.
     */
    protected static function deleteRules(Request $request): array
    {
        return [];
    }

    /**
     * Rules for querying records.
     */
    protected static function queryRules(Request $request): array
    {
        return [
            \'per_page\' => [\'sometimes\', \'integer\', \'min:1\', \'max:100\'],
            \'sort_by\' => [\'sometimes\', \'string\'],
            \'sort_order\' => [\'sometimes\', \'string\', \'in:asc,desc\'],
            \'filter\' => [\'sometimes\', \'array\'],
            \'search\' => [\'sometimes\', \'string\'],
            \'search_fields\' => [\'sometimes\', \'string\'],
            \'include\' => [\'sometimes\', \'string\'],
        ];
    }
}
',

            'middleware' => '<?php

namespace {{namespace}};

use {{baseMiddleware}};
use Illuminate\Http\Request;

class {{name}}Middleware extends {{baseMiddlewareName}}
{
    /**
     * Authorize creating a record.
     */
    // protected function authorizeCreate(Request $request): bool
    // {
    //     return auth()->user()?->can(\'{{nameLower}}.create\');
    // }

    /**
     * Authorize reading a record.
     */
    // protected function authorizeRead(Request $request): bool
    // {
    //     return auth()->user()?->can(\'{{nameLower}}.read\');
    // }
}
',

            'service' => '<?php

namespace {{namespace}};

use {{baseService}};

class {{name}}Service extends {{baseServiceName}}
{
    protected static string $model = {{modelName}}::class;

    // protected static ?int $cacheDuration = 3600;
    // protected static ?string $cacheTag = \'{{nameLower}}\';
}
',

            'observer' => '<?php

namespace {{namespace}};

use {{baseObserver}};
use {{modelNamespace}}\{{modelName}};

class {{name}}Observer extends {{baseObserverName}}
{
    public static function getObservedModel(): string
    {
        return {{modelName}}::class;
    }
}
',

            'migration' => '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\'{{table}}\', function (Blueprint $table) {
            $table->id();
            $table->uuid(\'uuid\')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\'{{table}}\');
    }
};
',
        ];

        return $stubs[$type] ?? '';
    }
}
