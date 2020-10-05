<?php

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::apiResource('employee', 'Api\EmployeeController');
Route::apiResource('supplier', 'Api\SupplierController');
Route::apiResource('category', 'Api\CategoryController');
Route::apiResource('product', 'Api\ProductController');
Route::apiResource('expense', 'Api\ExpenseController');
Route::apiResource('customer', 'Api\CustomerController');

// Salary Routes
Route::post('/salary/paid/{id}', 'Api\SalaryController@paid');
Route::get('/salary', 'Api\SalaryController@allSalary');
Route::get('/salary/{month}', 'Api\SalaryController@salaryByMonth');
Route::get('/edit-salary/{id}', 'Api\SalaryController@edit');
Route::post('/update-salary/{id}', 'Api\SalaryController@update');

//Stock Routes
Route::patch('/stock/{id}', 'Api\ProductController@updateStock');

//POS Route
Route::get('/category/product/{id}', 'Api\PosController@categoryProducts');

//Cart Routes
Route::get('/addToCart/{id}', 'Api\CartController@addToCart');
Route::get('/cart-products', 'Api\CartController@cartProducts');
Route::get('/cart/delete/{id}', 'Api\CartController@cartDelete');
Route::get('/cart/increment/{id}', 'Api\CartController@increment');
Route::get('/cart/decrement/{id}', 'Api\CartController@decrement');

Route::get('/vat', 'Api\CartController@vat');

// Order
Route::post('/order', 'Api\PosController@order');
Route::get('/today-order', 'Api\OrderController@todayOrder');
Route::get('/orders/{id}', 'Api\OrderController@orders');
Route::get('/order/details/{id}', 'Api\OrderController@orderDetails');

// Dashboard Routes
Route::get('/today/sell', 'Api\PosController@todaySell');
Route::get('/today/income', 'Api\PosController@todayIncome');
Route::get('/today/due', 'Api\PosController@todayDue');
Route::get('/total/expense', 'Api\PosController@expenses');
Route::get('/stockout/product', 'Api\PosController@stockOut');