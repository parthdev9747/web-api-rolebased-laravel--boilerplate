<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::post('reset-password', [ResetPasswordController::class, 'submitResetPasswordForm'])->name('password.update');

Route::get('api-reset-password', function () {
    dd('herererer');
})->name('api-password-reset');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    Route::resource('permission', PermissionController::class);
    Route::get('permission-destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.remove');

    Route::resource('roles', RoleController::class);
    Route::get('roles-destroy/{id}', [RoleController::class, 'destroy'])->name('roles.remove');

    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);

    Route::get('customers/datatable', [CustomerController::class, 'getDatatable']);
    Route::resource('customers', CustomerController::class);
});
