<?php


namespace atefe93\TowFactorAuth;


use atefe93\TowFactorAuth\Http\Responses\Responses;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use atefe93\TowFactorAuth\Authenticator\SessionAuth;
use atefe93\TowFactorAuth\Facades\AuthFacade;
use atefe93\TowFactorAuth\Facades\TokenGeneratorFacade;
use atefe93\TowFactorAuth\Facades\TokenSenderFacade;
use atefe93\TowFactorAuth\Facades\TokenStoreFacade;
use atefe93\TowFactorAuth\Facades\UserProviderFacade;
use atefe93\TowFactorAuth\Http\ResponderFacade;
use atefe93\TowFactorAuth\Http\Responses\VueResponses;
use atefe93\TowFactorAuth\TokenGenerator\FakeTokenGenerator;
use atefe93\TowFactorAuth\TokenGenerator\TokenGenerator;
use atefe93\TowFactorAuth\TokenSender\FakeTokenSender;
use atefe93\TowFactorAuth\TokenSender\TokenSender;
use atefe93\TowFactorAuth\TokenStore\FakeTokenStore;
use atefe93\TowFactorAuth\TokenStore\TokenStore;

class TowFactorAuthServiceProvider extends ServiceProvider
{
    private $namespace='atefe93/TowFactorAuth/Http/Controllers';

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/tow_factor_auth_config.php',
        'tow_factor_config'
        );

        UserProviderFacade::shouldProxyTo(UserProvider::class);
        AuthFacade::shouldProxyTo(SessionAuth::class);
        if (app()->runningUnitTests()){
            $tokenGenerator=FakeTokenGenerator::class;
            $tokenStore=FakeTokenStore::class;
            $tokenSender=FakeTokenSender::class;
        }else{
            $tokenGenerator=TokenGenerator::class;
            $tokenStore=TokenStore::class;
            $tokenSender=TokenSender::class;
        }
        TokenGeneratorFacade::shouldProxyTo($tokenGenerator);
        TokenStoreFacade::shouldProxyTo($tokenStore);
        TokenSenderFacade::shouldProxyTo($tokenSender);
        ResponderFacade::shouldProxyTo(Responses::class);

    }

    public function boot()
    {
        if (!$this->app->routesAreCached()){
            $this->defineRoutes();
        }

    }

    private function defineRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . './routes.php');
    }

}
