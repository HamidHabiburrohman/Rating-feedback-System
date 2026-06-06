<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        config([
            'auth.guards' => [
                'admin' => [
                    'driver' => 'session',
                    'provider' => 'admins',
                ],
                'employee' => [
                    'driver' => 'session',
                    'provider' => 'employees',
                ],
                'student' => [
                    'driver' => 'session',
                    'provider' => 'students',
                ],
            ],
            'auth.defaults.guard' => 'admin',
        ]);
    }
}