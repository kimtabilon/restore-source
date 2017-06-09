<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('cashier-access', function ($user) {
            return  $user->role->name == 'Cashier';
        });

        Gate::define('manager-access', function ($user) {
            return  $user->role->name == 'Manager';
        });

        Gate::define('receiving-coordinator-access', function ($user) {
            return  $user->role->name == 'Receiving Coordinator';
        });


        // HAVE COMMON ACCESS
        Gate::define('cashier-manager-access', function ($user) {
            return  $user->role->name == 'Cashier'||$user->role->name == 'Manager';
        });

        Gate::define('cashier-receiving-coordinator-access', function ($user) {
            return  $user->role->name == 'Cashier'||$user->role->name == 'Receiving Coordinator';
        });

        Gate::define('manager-receiving-coordinator-access', function ($user) {
            return  $user->role->name == 'Manager'||$user->role->name == 'Receiving Coordinator';
        });

    }
}
