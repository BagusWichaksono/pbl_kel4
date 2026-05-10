<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, function () {
            return new class implements LogoutResponseContract {
                public function toResponse($request) {
                    return redirect('/login');
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
