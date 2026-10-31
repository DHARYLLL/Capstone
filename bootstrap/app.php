<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\UpdateUserLastSeen;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            UpdateUserLastSeen::class,
        ]);
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        //
        // Only unauthenticated widget mutations are exempt. Administrative routes remain protected.
        $middleware->validateCsrfTokens(except: [
            'chat/*/ask',
            'chat/feedback',
            'api/chatbot/cancel-handoff',
        ]);
        // Trust Render's reverse proxy load balancer
        $middleware->trustProxies(
            at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isJsonEndpoint = static fn (Request $request): bool => $request->is('chat/*/ask')
            || $request->is('chat/feedback')
            || $request->is('get-messages/*')
            || $request->is('api/chatbot/*')
            || $request->is('admin/chat/*')
            || $request->is('staff/chats/*');

        $exceptions->shouldRenderJsonWhen($isJsonEndpoint);
        $exceptions->render(function (\Throwable $exception, Request $request) use ($isJsonEndpoint) {
            if (! $isJsonEndpoint($request)) {
                return null;
            }

            $status = $exception instanceof ValidationException
                ? 422
                : (method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
            $status = $status >= 400 && $status <= 599 ? $status : 500;
            $correlationId = (string) str()->uuid();

            report($exception);

            return response()->json([
                'success' => false,
                'message' => $status === 500
                    ? 'The service is temporarily unavailable.'
                    : $exception->getMessage(),
                'errors' => $exception instanceof ValidationException
                    ? $exception->errors()
                    : [],
                'code' => $status,
                'correlation_id' => $correlationId,
            ], $status);
        });
    })->create();
