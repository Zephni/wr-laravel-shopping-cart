<?php

namespace WebRegulate\LaravelShoppingCart;

use App\Http\Middleware\NeptuneBootSetup;
use Livewire\Livewire;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use WebRegulate\LaravelShoppingCart\Livewire\ShoppingCartBasket;

class WRLaravelShoppingCartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/config/wr-laravel-shopping-cart.php', 'wr-laravel-shopping-cart');

        // Merge wrla info and error logging channels
        $this->app->make('config')->set('logging.channels.wrla-info', [
            'driver' => 'daily',
            'path' => storage_path('logs/wr-laravel-shopping-cart/wr-laravel-shopping-cart-info.log'),
            'level' => 'debug',
        ]);

        $this->app->make('config')->set('logging.channels.wrla-error', [
            'driver' => 'daily',
            'path' => storage_path('logs/wr-laravel-shopping-cart/wr-laravel-shopping-cart-error.log'),
            'level' => 'debug',
        ]);

        // Register Livewire
        $this->app->register(\Livewire\LivewireServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {        
        // Publish assets
        $this->publishableAssets();

        // Main setup - Loading migrations, routes, views, etc.
        $this->mainSetup();

        // Pass variables to all routes within this package
        $this->passVariablesToViews();

        // Provide blade directives
        $this->provideBladeDirectives();

        // Post boot calls
        $this->app->booted(function (): void {
            $this->postBootCalls();
        });
    }

    /**
     * Set publishable assets
     * @return void
     */
    protected function publishableAssets(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/config/wr-laravel-shopping-cart.php' => config_path('wr-laravel-shopping-cart.php'),
        ], 'wr-laravel-shopping-cart-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'wr-shopping-cart-migrations');
    }

    /**
     * Main setup - Loading assets, routes, etc.
     * @return void
     */
    protected function mainSetup(): void
    {
        // Commands
        // $this->commands([
            
        // ]);

        // Load routes
        Route::middleware('web')->group(function (): void {
            $this->loadRoutesFrom(__DIR__ . '/routes/wr-laravel-shopping-cart-routes.php');
        });

        // Register validation rules
        $this->registerValidationRules();

        // Load views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'wr-laravel-shopping-cart');

        // Livewire component registering and asset injection
        Livewire::component('shopping-cart-basket', ShoppingCartBasket::class);
        Livewire::forceAssetInjection();

        // Load custom blade directives
        // Blade::component('wr-laravel-shopping-cart.something-here', 'something-here');
    }

    /**
     * Register custom validation rules
     *
     * @return void
     */
    protected function registerValidationRules(): void
    {
        // Register custom validation rules here
    }

    /**
     * Pass variables to all views within this package
     * @return void
     */
    protected function passVariablesToViews(): void
    {
        // Share variables with all views within this package
        // view()->composer(['wr-laravel-shopping-cart::*', '*wr-laravel-shopping-cart.*'], function ($view) {
            
        // });
    }

    /**
     * Provide blade directives
     * @return void
     */
    protected function provideBladeDirectives(): void
    {
        // Blade directives
    }

    /**
     * Post boot calls
     * @return void
     */
    protected function postBootCalls(): void
    {
        // Post boot calls
    }
}
