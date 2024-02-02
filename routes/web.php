<?php

use App\Http\Controllers\BarkController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UserController::class, 'index'])->name('users.index');

Route::prefix('users')->group(function () {
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/{id}/barks', [BarkController::class, 'store'])->name('barks.store');
    Route::get('/{id}/barks', [UserController::class, 'loadBarks'])->name('users.loadBarks');
});
