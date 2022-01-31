<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay;
use Crazymeeks\MongoDB\Facades\Connection as MongoConnection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCustom();
    }

    protected function registerCustom()
    {
        $database = config('app.env') == 'testing' ? ('testing_' . env('MONGODB_NAME')) : env('MONGODB_NAME');
        MongoConnection::setUpConnection(env('MONGODB_HOST'), ['username' => env('MONGODB_USERNAME'), 'password' => env('MONGODB_PASSWORD')], [])
          ->setDefaultDatabase($database)
          ->connect();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        $this->app->bind(Dragonpay::class, function($app){
            $merchant_account = [
                    'merchantid' => env('DP_PROD_ID'),
                    'password'   => env('DP_PROD_KEY'),
            ];
            $sandbox = false;
            if (App::environment(['local', 'testing', 'staging'])) {
                
                $sandbox = true;
                $merchant_account = [
                    'merchantid' => env('DP_TEST_ID'),
                    'password'   => env('DP_TEST_KEY'),
                ];
            }

            return new Dragonpay($merchant_account, $sandbox);
        });

        if($this->app->environment('production') || $this->app->environment('staging')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');

        }
    }
}
