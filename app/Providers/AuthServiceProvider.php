<?php

namespace App\Providers;

use Laravel\Passport\Passport;
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

        Passport::routes();
        Passport::tokensExpireIn(now()->addMinutes(30));
        Passport::refreshTokensExpireIn(now()->addDays(1));

        Passport::tokensCan([
            'manager'    => 'All Privileges',
            'list-car'   => 'List the cars',
            'create-car' => 'Create a car',
            'update-car' => 'Update a car',
            'delete-car' => 'Delete a car'
        ]);

        Passport::personalAccessClientId('client-id');
    }
}
