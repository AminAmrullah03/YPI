<?php

namespace App\Providers;

use App\Auth\UsernameUserProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
        // Set Carbon locale ke Bahasa Indonesia
        Carbon::setLocale('id');

        // Daftarkan custom provider untuk login menggunakan username
        Auth::provider('username', function ($app, array $config) {
            return new UsernameUserProvider(
                $app['hash'],
                $config['model']
            );
        });

        // Register Observers
        \App\Models\Siswa::observe(\App\Observers\SiswaObserver::class);
        \App\Models\Guru::observe(\App\Observers\GuruObserver::class);
    }
}
