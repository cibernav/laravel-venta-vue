<?php

namespace App\Providers;

use Carbon\Carbon;
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
        //Definir el tiempo de expiracion del token
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        // //Definir el tiempo que tiene para refrescar el token expirado
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        //
        Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(1));
    }
}
