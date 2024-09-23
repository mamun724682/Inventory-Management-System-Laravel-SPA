<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'pageTitle' => 'Home',
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('salaries', SalaryController::class);

    // Order
    Route::apiResource('orders', OrderController::class);
    Route::put('orders/{order}/settle', [OrderController::class, 'settle'])->name('orders.settle');
    Route::put('orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');

    // Transaction
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // Carts
    Route::get('pos', [CartController::class, 'index'])->name('carts.index');
    Route::post('carts/{productId}', [CartController::class, 'addToCart'])->name('carts.store');
    Route::put('carts/{cart}', [CartController::class, 'updateQuantity'])->name('carts.update');
    Route::delete('carts/{cart}', [CartController::class, 'delete'])->name('carts.delete');
    Route::delete('carts/delete/all', [CartController::class, 'deleteForUser'])->name('carts.delete.all');
    Route::put('carts/{cart}/increment', [CartController::class, 'incrementQuantity'])->name('carts.increment');
    Route::put('carts/{cart}/decrement', [CartController::class, 'decrementQuantity'])->name('carts.decrement');
});

require __DIR__ . '/auth.php';
