<?php

use Illuminate\Support\Facades\Route;

Route::prefix('reach-seeder')->group(function () {
    Route::post('model/create', 'LaravelPro\ReachSeeder\FactoryController@create');
    Route::post('model/make', 'LaravelPro\ReachSeeder\FactoryController@make');
});
