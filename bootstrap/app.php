<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //le middleware de redirection basé sur le rôle et le statut de l'utilisateur
        $middleware->append(\App\Http\Middleware\RedirectBasedOnRoleAndStatus::class);
        
        // Enregistrer les middlewares pour les rôles et les statuts
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'status' => \App\Http\Middleware\CheckStatus::class,
            'offre.ownership' => \App\Http\Middleware\CheckOffreOwnership::class,
        ]);
            

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
