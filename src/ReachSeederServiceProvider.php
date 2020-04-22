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
        if (app()->isProduction()) {
            throw new \RuntimeException('Reach Seeder is not allowed running in production');
        }
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
