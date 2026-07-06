<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Inisialisasi konfigurasi dasar aplikasi
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    });

// 2. AMBIL INSTANCE APLIKASI SEBELUM DI-CREATE/DIPANGGIL
$instance = $app->create();

// 3. PAKSA LARAVEL 13 MENGGUNAKAN FOLDER TMP VERCEL UNTUK STORAGE
$instance->useStoragePath(env('APP_STORAGE', base_path('storage')));

// 4. Kembalikan instance yang sudah dimodifikasi
return $instance;