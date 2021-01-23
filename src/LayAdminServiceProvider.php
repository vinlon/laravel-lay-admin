<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Vinlon\Laravel\LayAdmin\Commands\CreateAdminUser;
use Vinlon\Laravel\LayAdmin\Commands\ResetPassword;
use Vinlon\Laravel\LayAdmin\Exceptions\LayAdminException;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class LayAdminServiceProvider extends ServiceProvider
{
    const LAY_ADMIN = 'lay-admin';

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function register()
    {
        // publish assets
        $this->publishes([
            __DIR__ . '/../publishes/assets' => public_path('assets'),
            __DIR__ . '/../publishes/lay-admin' => public_path(self::LAY_ADMIN),
        ], 'public');

        // publish config
        $this->publishes([
            $this->getConfigPath() => config_path(self::LAY_ADMIN . '.php'),
        ], 'config');
    }

    public function boot()
    {
        // load routes
        $routePrefix = config(self::LAY_ADMIN . '.route_prefix');
        Route::namespace('Vinlon\Laravel\LayAdmin\Controllers')
            ->prefix($routePrefix)
            ->group(__DIR__ . '/routes/lay-admin-web.php')
        ;
        Route::namespace('Vinlon\Laravel\LayAdmin\Controllers')
            ->prefix('lay-admin')
            ->group(__DIR__ . '/routes/lay-admin-api.php')
        ;

        // load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // load views
        $this->loadViewsFrom(__DIR__ . '/views', self::LAY_ADMIN);

        // merge auth config
        $this->mergeAuthConfig();

        // merge lay-admin config
        $this->mergeConfigFrom($this->getConfigPath(), self::LAY_ADMIN);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdminUser::class,
                ResetPassword::class,
            ]);
        }
    }

    private function getConfigPath()
    {
        return __DIR__ . '/../publishes/config/' . self::LAY_ADMIN . '.php';
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
            'model' => AdminUser::class,
        ];
        $layAdminGuard = [
            'driver' => 'jwt',
            'provider' => $providerName,
        ];
        $config = $this->app->make('config');
        $authConfig = $config->get($authConfigKey, []);
        if (array_key_exists($providerName, $authConfig['providers'])) {
            throw new LayAdminException("the provider name {$providerName} is used");
        }
        if (array_key_exists($guardName, $authConfig['guards'])) {
            throw new LayAdminException("the guard name {$guardName} is used");
        }
        $authConfig['providers'][$providerName] = $adminUsersProvider;
        $authConfig['guards'][$guardName] = $layAdminGuard;
        $config->set($authConfigKey, $authConfig);
    }
}
