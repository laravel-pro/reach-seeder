<?php


namespace LaravelPro\ReachSeeder;


use Illuminate\Support\ServiceProvider;

class ReachSeederServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
