<?php

namespace Laralum\Social;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laralum\Permissions\PermissionsChecker;
use Laralum\Social\Models\Settings;
use Laralum\Social\Policies\SettingsPolicy;

class SocialServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Settings::class => SettingsPolicy::class,
    ];

    /**
     * The mandatory permissions for the module.
     *
     * @var array
     */
    protected $permissions = [
        [
            'name' => 'Update Social Settings',
            'slug' => 'laralum::social.settings',
            'desc' => 'Allows updating the social settings',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->loadTranslationsFrom(__DIR__.'/Translations', 'laralum_social');

        $this->loadViewsFrom(__DIR__.'/Views', 'laralum_social');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Routes/web.php';
        }

        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register('Laravel\\Socialite\\SocialiteServiceProvider');

        // Make sure the permissions are OK
        PermissionsChecker::check($this->permissions);
    }

    /**
     * I cheated this comes from the AuthServiceProvider extended by the App\Providers\AuthServiceProvider.
     *
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
