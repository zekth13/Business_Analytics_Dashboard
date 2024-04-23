<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/sales', 'SalesController@index')->name('sales');
Route::get('/sales/getSalesSummaryPerMonth', 'SalesController@getSalesSummaryPerMonth')->name('monthly-sales');
Route::get('/sales/getMonthlySalesGrowth', 'SalesController@getMonthlySalesGrowth')->name('monthly-sales-growth');

Route::get('/outlets', 'SalesController@outletIndex')->name('outlets');
Route::get('/outlets/getOutletSales', 'SalesController@getOutletSales')->name('outlet-total-Sales');
Route::get('/outlets/getTotalSalesByState', 'SalesController@getTotalOutletsByStateYearly')->name('outlet-sales-by-state-yearly');
Route::get('/outlets/getTotalSalesByStateMonthly', 'SalesController@getTotalSalesByStateMonthly')->name('outlet-sales-by-state-monthly');

Route::get('/products', 'SalesController@products')->name('products');
Route::get('/products/getProductSalesByProductName', 'SalesController@getProductSalesByProductName')->name('product-sales');
Route::get('/products/getProductSalesByProductCategory', 'SalesController@getProductSalesByProductCategory')->name('product-category-sales');
Route::get('/products/getAllProductCategory', 'SalesController@getAllProductCategory');   //Unused
Route::get('/products/getAllProductName', 'SalesController@getAllProductName');   //Unused
Route::get('/products/getProductAnnualSales', 'SalesController@getProductAnnualSales')->name('product-annual-sales');
Route::get('/products/getProductQuarterlySales', 'SalesController@getProductQuarterlySales')->name('product-quarterly-sales');
Route::get('/products/getProductMonthlySales', 'SalesController@getProductMonthlySales')->name('product-monthly-sales');

Route::get('/suppliers', 'SuppliersController@suppliers')->name('suppliers');
Route::get('/suppliers/getSupplierNameAndNo', 'SuppliersController@getSupplierNameAndNo')->name('supplier-name-no');
Route::get('/suppliers/getTop10Supplier', 'SuppliersController@getTop10Supplier')->name('top10-supplier');
Route::get('/suppliers/getSupplierProductSales', 'SuppliersController@getSupplierProductSales')->name('supplier-product-sales');
Route::get('/suppliers/getAllSupplierDetails', 'SuppliersController@getAllSupplierDetails');   //Unused



Route::get('/inventory', 'inventoryController@index')->name('inventory');
Route::get('/inventory/getChart', 'inventoryController@getChart')->name('getChart');
Route::get('/inventory/getTable', 'inventoryController@getTable')->name('getTable');
Route::get('/inventory/getClass', 'inventoryController@getClass')->name('getClass');

Route::get('/reports', 'ReportController@index')->name('reports');
Route::get('/reports/sales-summary', 'ReportController@salesSummaryReport')->name('sales-summary');
Route::get('/reports/product-category', 'ReportController@productCategoryReport')->name('product-category');
Route::get('/reports/outlet-performance', 'ReportController@outletPerformanceReport')->name('outlet-performance');
Route::get('/reports/supplier-performance', 'ReportController@supplierPerformanceReport')->name('supplier-performance');
Route::get('/reports/custom-report', 'ReportController@customReport')->name('custom-report');




Route::get('/about', function () {
    return view('about');
})->name('about');
