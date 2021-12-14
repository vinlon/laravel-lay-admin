<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Vinlon\Laravel\LayAdmin\Commands\AdminControllerMakeCommand;
use Vinlon\Laravel\LayAdmin\Commands\ResetPassword;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class LayAdminServiceProvider extends ServiceProvider
{
    public const LAY_ADMIN = 'lay-admin';

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
            $this->getConfigPath() => config_path('captcha.php'),
            __DIR__ . '/../publishes/stubs' => base_path('stubs'),
        ], 'config');

        // publish stubs
        $this->publishes([
            __DIR__ . '/../publishes/stubs' => base_path('stubs'),
        ], 'stub');
    }

    public function boot()
    {
        // load routes
        $routePrefix = config(self::LAY_ADMIN . '.route_prefix');
        if (empty($routePrefix)) {
            $routePrefix = 'admin';
        }
        Route::namespace('Vinlon\Laravel\LayAdmin\Controllers')
            ->prefix($routePrefix)
            ->group(__DIR__ . '/routes/lay-admin-web.php')
        ;
        Route::namespace('Vinlon\Laravel\LayAdmin\Controllers')
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
                ResetPassword::class,
                AdminControllerMakeCommand::class,
            ]);
        }
    }

    private function getConfigPath()
    {
        return __DIR__ . '/../publishes/config/' . self::LAY_ADMIN . '.php';
    }

    /**
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

        $authConfig['providers'][$providerName] = $adminUsersProvider;
        $authConfig['guards'][$guardName] = $layAdminGuard;
        $config->set($authConfigKey, $authConfig);
    }
}
