<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * If we want to add a abilty to some suers we can change this section
         * like if later in app we want user to see the edit section
         * we can replace hasRole with hasAnyRoles 
         *
         **/

        // Gate for managing users
        Gate::define('manage-users', function ($user) {
            return $user->hasAnyRoles(['admin', 'editor']);
        });

        // Define a Gate to edit users
        Gate::define('edit-users', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-users', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
