<?php

namespace Uncover\ModularMonolith\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Uncover\ModularMonolith\Traits\ModuleMiddlewareTrait;

abstract class ModularMiddleware
{
    use ModuleMiddlewareTrait;

    /**
     * Map of actions to their corresponding authorization methods.
     * Override this in child classes.
     */
    protected array $actionMethodMap = [
        'create' => 'authorizeCreate',
        'read' => 'authorizeRead',
        'update' => 'authorizeUpdate',
        'delete' => 'authorizeDelete',
        'query' => 'authorizeQuery',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $action The action being performed
     * @return Response
     */
    public function handle(Request $request, Closure $next, ?string $action = null): Response
    {
        // Normalize the request
        $this->normalizeRequest($request, $action);

        // Determine action from route if not provided
        if ($action === null) {
            $action = $this->determineAction($request);
        }

        // Authorize the action
        if ($this->hasMiddlewareMethod($action)) {
            $method = $this->getMiddlewareMethod($action);
            $authorized = $this->$method($request);
            
            if (!$authorized) {
                return $this->denyAccess();
            }
        }

        return $next($request);
    }

    /**
     * Determine action from request method and route.
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()?->getName() ?? '';

        // Check if action is in route name
        foreach (['create', 'read', 'update', 'delete', 'query'] as $action) {
            if (str_contains($routeName, ".{$action}")) {
                return $action;
            }
        }

        // Fall back to HTTP method mapping
        return match ($method) {
            'POST' => 'create',
            'GET' => $request->route('id') ? 'read' : 'query',
            'PATCH', 'PUT' => 'update',
            'DELETE' => 'delete',
            default => 'query',
        };
    }

    /**
     * Default authorization methods.
     * Override these in child classes.
     */
    protected function authorizeCreate(Request $request): bool
    {
        return true;
    }

    protected function authorizeRead(Request $request): bool
    {
        return true;
    }

    protected function authorizeUpdate(Request $request): bool
    {
        return true;
    }

    protected function authorizeDelete(Request $request): bool
    {
        return true;
    }

    protected function authorizeQuery(Request $request): bool
    {
        return true;
    }

    /**
     * Deny access response.
     * Override in child class for custom response.
     */
    protected function denyAccess(): Response
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 403);
    }
}
