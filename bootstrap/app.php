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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'all_admin' => \App\Http\Middleware\AllAdminMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'section_admin' => \App\Http\Middleware\SectionAdminMiddleware::class,
            'edu' => \App\Http\Middleware\EduMiddleware::class,
            'edu_admin' => \App\Http\Middleware\EduAdminMiddleware::class,
            'school_admin' => \App\Http\Middleware\SchoolAdminMiddleware::class,
            'school_sign' => \App\Http\Middleware\SchoolSignMiddleware::class,
            'school_other_sign' => \App\Http\Middleware\SchoolOtherSignMiddleware::class,
        ]);        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
