<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Psr\Log\LogLevel;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append("\App\Http\Middleware\SetContext");
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->level(PDOException::class, LogLevel::CRITICAL);

        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 422); 
        });

    })->create();
