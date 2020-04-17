<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/reach-seeder')->group(function () {
    Route::post('model/create', 'LaravelPro\ReachSeeder\FactoryController@create')->name('model.create');
    Route::post('model/make', 'LaravelPro\ReachSeeder\FactoryController@make')->name('model.make');
});
