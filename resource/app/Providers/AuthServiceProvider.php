<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ImplicitGrant;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
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

        // By default, client should only be able to do this and nothing more, except explitly stated in the scopes param
        Passport::setDefaultScope([
            'view-posts'
        ]);

        Passport::tokensCan([
            'view-posts' => 'View Article posts',
            'view-users' => 'View a list of all the users on the resource'
        ]);


        // typically, the consuming application needs to make request for a token using javascript or some front end languag 
        // Passport::enableImplicitGrant();
        //Passport::enableImplicitGrant();
        $this->app->make(AuthorizationServer::class)->enableGrantType(
            $this->makeImplicitGrant(),
        );

   
    }

    /**
     * Create and configure an instance of the Implicit grant.
     *
     * @return \League\OAuth2\Server\Grant\ImplicitGrant
     * 
     * Do this to enable passport append ? to your callback instead of the default # it returns which the server cannot read 
     */
    protected function makeImplicitGrant()
    {
        return new ImplicitGrant(Passport::tokensExpireIn(), '?');  
    }
}
