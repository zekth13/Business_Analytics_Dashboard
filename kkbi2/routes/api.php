<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get("students", function (){
    return "this is student api";
});
// Route::get('/sales', 'SalesController@index')->name('sales');
Route::get("forecast", 'forecastcontroller@index')->name('forecast');
Route::get("forecast/loading", 'forecastcontroller@load')->name('loading');
Route::get("forecast/monthly", 'forecastcontroller@monthlyforecast')->name('monthly');
Route::get("forecast/annual", 'forecastcontroller@annualforecast')->name('annual');
Route::get("storeforecast", 'storeforecastcontroller@index')->name('storeforecast');
Route::get("storeforecast/monthlystoreforecast", 'storeforecastcontroller@monthlystoreforecast')->name('monthlystore');
Route::get("storeforecast/yearlystoreforecast", 'storeforecastcontroller@yearlystoreforecast')->name('yearlystore');
Route::get('sendtoflask', 'forecastcontroller@storeforecast')->name('store');