<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyRateLimiter();
    }

    /**
     * @return void
     */
    protected function applyRateLimiter(): void
    {
        RateLimiter::for(
            'api',
            fn (Request $request) => Limit::perMinute(10)
            ->by($request->user()?->id ?: $request->ip())
        );
    }
}
