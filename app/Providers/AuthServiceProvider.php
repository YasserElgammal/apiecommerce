<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Merchant Gate
        Gate::define('merchant', function (User $user) {
            if ($user->role->name == 'Merchant') {
                return true;
            }
        });
        // User Gate
        Gate::define('normal-user', function (User $user) {
            if ($user->role->name == 'User') {
                return true;
            }
        });
    }
}
