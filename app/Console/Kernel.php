<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustProxies::class,
        // \Illuminate\Http\Middleware\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // \App\Http\Middleware\EncryptCookies::class,
            // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            // \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            // \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $middlewareAliases = [
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        // 'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        // 'cache' => \Illuminate\Http\Middleware\Middleware::class,
        // 'can' => \Illuminate\Auth\Middleware\Authorize::class,
        // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // 'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Near-realtime alerts sync every minute
        $schedule->command('sync:mysql-to-postgres --alerts')
                ->everyMinute()
                ->withoutOverlapping()
                ->runInBackground()
                ->onSuccess(function () {
                    Log::info('Alerts sync completed successfully');
                })
                ->onFailure(function () {
                    Log::error('Alerts sync failed');
                });

        // Daily full sync at 2 AM (for other tables)
        $schedule->command('sync:mysql-to-postgres --sites --ai-alerts --reports')
                ->daily()
                ->at('02:00')
                ->withoutOverlapping()
                ->runInBackground()
                ->onSuccess(function () {
                    Log::info('Daily database sync completed successfully');
                })
                ->onFailure(function () {
                    Log::error('Daily database sync failed');
                });

        // Weekly full sync on Sundays at 3 AM
        $schedule->command('sync:mysql-to-postgres --all --force')
                ->weekly()
                ->sundays()
                ->at('03:00')
                ->withoutOverlapping()
                ->runInBackground();
    }
} 