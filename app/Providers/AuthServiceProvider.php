<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        // Define settings permission
        Gate::define('update-settings', function ($user) {
            return $user->isAdmin(); // Pake method yang udah ada di User model
        });

        Gate::define('view-settings', function ($user) {
            return $user->isAdmin();
        });
    }
}