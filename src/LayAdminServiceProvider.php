<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Vinlon\Laravel\LayAdmin\Commands\CreateAdminUser;
use Vinlon\Laravel\LayAdmin\Commands\ResetPassword;
use Vinlon\Laravel\LayAdmin\Exceptions\LayAdminException;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class LayAdminServiceProvider extends ServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function register()
    {
    }

    public function boot()
    {
        //load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/lay-admin.php');

        //load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // load views
        $this->loadViewsFrom(__DIR__ . '/views', 'lay-admin');

        $this->mergeAuthConfig();

        // publish assets
        $this->publishes([
            __DIR__ . '/../publishes/assets' => public_path('assets'),
            __DIR__ . '/../publishes/lay-admin' => public_path('lay-admin'),
        ], 'public');


        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdminUser::class,
                ResetPassword::class,
            ]);
        }
    }

    /**
     * @throws LayAdminException
     * @throws BindingResolutionException
     */
    private function mergeAuthConfig()
    {
        $authConfigKey = 'auth';
        $providerName = 'admin-users';
        $guardName = 'lay-admin';
        $adminUsersProvider = [
            'driver' => 'eloquent',
            'model' => AdminUser::class
        ];
        $layAdminGuard = [
            'driver' => 'jwt',
            'provider' => $providerName,
        ];
        $config = $this->app->make('config');
        $authConfig = $config->get($authConfigKey, []);
        if (array_key_exists($providerName, $authConfig['providers'])) {
            throw new LayAdminException("the provider name $providerName is used");
        }
        if (array_key_exists($guardName, $authConfig['guards'])) {
            throw new LayAdminException("the guard name $guardName is used");
        }
        $authConfig['providers'][$providerName] = $adminUsersProvider;
        $authConfig['guards'][$guardName] = $layAdminGuard;
        $config->set($authConfigKey, $authConfig);
    }
}