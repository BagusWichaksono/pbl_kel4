<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\HtmlString;

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
        FilamentIcon::register([
            'panels::sidebar.collapse-button' => new HtmlString('
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3.75" y="3.75" width="16.5" height="16.5" rx="4.25" stroke="currentColor" stroke-width="2.2"/>
                    <path d="M9 7.7v8.6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M15 9.2l-3 2.8 3 2.8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            '),
            'panels::sidebar.expand-button' => new HtmlString('
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3.75" y="3.75" width="16.5" height="16.5" rx="4.25" stroke="currentColor" stroke-width="2.2"/>
                    <path d="M15 7.7v8.6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M9 9.2l3 2.8-3 2.8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            '),
        ]);
    }
}
