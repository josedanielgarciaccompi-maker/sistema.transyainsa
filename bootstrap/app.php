<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Confía en proxy si usas Nginx/Cloudflare
        $middleware->trustProxies(at: '*');

        // Añade tus middlewares SIN reemplazar el grupo
        /*$middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
*/
        // Si deshabilitaste CSRF, al menos NO excluyas /livewire/*
        // Mejor re-habilita CSRF y deja que Livewire lo maneje:
        // $middleware->validateCsrfTokens(except: []);  // sin exclusiones globales
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
