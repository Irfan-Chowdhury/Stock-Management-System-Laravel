<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard','Backend\HomeController@index')->name('dashboard');

Route::group(['namespace'=>'Backend'], function () {
    
    Route::group(['prefix' => 'category'], function () {
        Route::get('/','CategoryController@index')->name('category.index');
        Route::post('/store','CategoryController@store')->name('category.store');
        Route::get('/edit/{id}','CategoryController@edit')->name('category.edit');
        Route::post('/update/{id}','CategoryController@update')->name('category.update');
        Route::post('/delete/{id}','CategoryController@destroy')->name('category.delete');
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('/','CompanyController@index')->name('company.index');
        Route::post('/store','CompanyController@store')->name('company.store');
        Route::get('/edit/{id}','CompanyController@edit')->name('company.edit');
        Route::post('/update/{id}','CompanyController@update')->name('company.update');
        Route::post('/delete/{id}','CompanyController@destroy')->name('company.delete');
    });

    Route::group(['prefix' => 'item'], function () {
        Route::get('/create','ItemController@create')->name('item.create');
        Route::post('/store','ItemController@store')->name('item.store');
    });
   
    Route::group(['prefix' => 'stockin'], function () {
        Route::get('/create','StockInController@create')->name('stockin.create');
        Route::get('/company-wise-item','StockInController@companyWiseItem')->name('company-wise-item'); //AJAX
        Route::get('/show-reorder-level','StockInController@showReorderLevel_avlQuantity')->name('show-reorder-level'); //AJAX
        Route::post('/store','StockInController@store')->name('stockin.store');
    });

    Route::group(['prefix' => 'stockout'], function () {
        Route::get('/create','StockOutController@create')->name('stockout.create');
        // Route::get('/company-wise-item','StockInController@companyWiseItem')->name('company-wise-item'); //AJAX
        // Route::get('/show-reorder-level','StockInController@showReorderLevel_avlQuantity')->name('show-reorder-level'); //AJAX
        Route::get('/company-wise-item-stockout','StockInController@companyWiseItem')->name('company-wise-item-stockout'); //AJAX
        Route::get('/show-reorder-level-stockout','StockInController@showReorderLevel_avlQuantity')->name('show-reorder-level-stockout'); //AJAX
        // Route::get('/show','StockOutController@show')->name('stockout.show');
        Route::post('/add','StockOutController@add')->name('stockout.add');
        Route::post('/sell','StockOutController@sell')->name('stockout.sell');
        Route::post('/damage','StockOutController@damage')->name('stockout.damage');
        Route::post('/lost','StockOutController@lost')->name('stockout.lost');
    });

    //Search & View Items
    Route::group(['prefix' => 'searchAndViewItems'], function () {
        Route::get('/index','searchAndViewItemsController@index')->name('searchAndViewItems.index');
        Route::get('/show','searchAndViewItemsController@show')->name('searchAndViewItems.show');
    });
    
    //view sales between two dates
    Route::group(['prefix' => 'view-sales-between-dates'], function () {
        Route::get('/index','ViewSalesBetweenDatesController@index')->name('view-sales-between-date.index'); 
        Route::get('/show','ViewSalesBetweenDatesController@show')->name('view-sales-between-date.show');
    });


});