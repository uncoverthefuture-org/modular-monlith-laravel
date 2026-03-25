<?php

namespace Uncover\ModularMonolith\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Uncover\ModularMonolith\Console\Commands\MakeModularCommand;
use Uncover\ModularMonolith\Http\Controllers\ModularController;
use Uncover\ModularMonolith\Models\ModularModel;

class ModularServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/modular.php',
            'modular'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerRouteMacros();
        $this->registerCommands();
        $this->offerPublishing();
    }

    /**
     * Register route macros for modular resources.
     */
    protected function registerRouteMacros(): void
    {
        Route::macro('moduleResource', function ($name, $controller, $options = []) {
            $middleware = $options['middleware'] ?? [];
            
            // Convert string middleware to array
            if (is_string($middleware)) {
                $middleware = [$middleware];
            }

            // Define the five standard REST endpoints
            Route::group(['prefix' => $name, 'middleware' => $middleware], function () use ($controller) {
                // Create (single or batch)
                Route::post('/', [$controller, 'create'])
                    ->name("{$name}.create");

                // Query/List with pagination
                Route::get('/', [$controller, 'query'])
                    ->name("{$name}.query");

                // Read single
                Route::get('/{id}', [$controller, 'read'])
                    ->name("{$name}.read");

                // Update
                Route::patch('/{id}', [$controller, 'update'])
                    ->name("{$name}.update");

                // Delete (single or batch)
                Route::delete('/{id?}', [$controller, 'delete'])
                    ->name("{$name}.delete");
            });
        });
    }

    /**
     * Register artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModularCommand::class,
            ]);
        }
    }

    /**
     * Setup publishable resources.
     */
    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/modular.php' => config_path('modular.php'),
        ], 'modular-config');

        // Publish base classes (optional - if user wants to customize them)
        $this->publishes([
            __DIR__ . '/../Http/Controllers/ModularController.php' => app_path('Http/Controllers/ModularController.php'),
            __DIR__ . '/../Models/ModularModel.php' => app_path('Models/ModularModel.php'),
            __DIR__ . '/../Validations/ModularValidation.php' => app_path('Validations/ModularValidation.php'),
            __DIR__ . '/../Middleware/ModularMiddleware.php' => app_path('Http/Middleware/ModularMiddleware.php'),
            __DIR__ . '/../Services/ModularService.php' => app_path('Services/ModularService.php'),
            __DIR__ . '/../Observers/ModularObserver.php' => app_path('Observers/ModularObserver.php'),
            __DIR__ . '/../Traits/ModuleMiddlewareTrait.php' => app_path('Traits/ModuleMiddlewareTrait.php'),
        ], 'modular-base-classes');

        // Publish stubs
        $this->publishes([
            __DIR__ . '/../stubs' => base_path('stubs/modular'),
        ], 'modular-stubs');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../../lang' => base_path('lang/vendor/modular'),
        ], 'modular-lang');
    }
}
