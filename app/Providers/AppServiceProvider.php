<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
		Schema::defaultStringLength(191);
		Blade::directive('feather',function($name){
            return '<i class="link-arrow" data-feather="'.$name.'"></i>';
        });
		Blade::directive('required',function(){
            return '<span class="text-danger">*</span>';
        });
		Blade::directive('new',function(){
            return '(<span class="text-success">New</span>)';
        });
		
		View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }
}
