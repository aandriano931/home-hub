<?php

namespace App\Providers;

use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('view-admin', function ($user) {
            return in_array($user->email, explode(',', config('user.allowed_admins'))) && $user->hasVerifiedEmail();
        });
        Gate::define('view-perso', function ($user) {
            return in_array($user->email, explode(',', config('user.allowed_admins')));
        });
    }
}
