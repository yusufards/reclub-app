<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias Middleware
        $middleware->alias([
            'admin' => EnsureAdmin::class,
        ]);

        // Opsional: Redirect tamu ke halaman login jika mengakses route auth
        $middleware->redirectGuestsTo(fn(Request $request) => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /**
         * MENANGANI ERROR 404 (NOT FOUND)
         * Jika room dihapus host saat user mencoba akses URL-nya,
         * sistem akan melempar 404. Kita tangkap dan redirect ke Home.
         */
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // Cek jika URL yang diakses berawalan 'rooms/' atau 'venues/'
            if ($request->is('rooms/*') || $request->is('venues/*')) {
                return redirect()->route('home')
                    ->with('error', 'Room atau Halaman yang Anda cari sudah tidak tersedia atau telah dihapus.');
            }

            // Biarkan 404 standar untuk halaman lain
            return null;
        });
    })->create();