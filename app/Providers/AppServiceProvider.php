<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Blade custom directives for isAdmin
        
        Blade::directive('isAdmin', function() {
            return "<?php if(Auth::user()->isAdmin()): ?>";
        });
            
            Blade::directive('endisAdmin', function() {
                return "<?php endif; ?>";
            });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Blade::if('isAdmin', function(){
            if(Auth::check())
                return Auth::user()->is_admin===1?true:false;
                else
                    return false;
        });
    }
}
