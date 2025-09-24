<?php

use Illuminate\Support\Facades\Route;
use Schoolees\Psgc\Http\Controllers\RegionController;
use Schoolees\Psgc\Http\Controllers\ProvinceController;
use Schoolees\Psgc\Http\Controllers\CityController;
use Schoolees\Psgc\Http\Controllers\BarangayController;


Route::prefix(config('psgc.api_prefix', 'psgc'))->group(function () {
    Route::get('/regions',   [RegionController::class,   'show']);
    Route::get('/provinces', [ProvinceController::class, 'show']);
    Route::get('/cities',    [CityController::class,     'show']);
    Route::get('/barangays', [BarangayController::class, 'show']);
    
});




