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

// Salary Routes
Route::post('/salary/paid/{id}', 'Api\SalaryController@paid');
Route::get('/salary', 'Api\SalaryController@allSalary');
Route::get('/salary/{month}', 'Api\SalaryController@salaryByMonth');
Route::get('/edit-salary/{id}', 'Api\SalaryController@edit');
Route::post('/update-salary/{id}', 'Api\SalaryController@update');