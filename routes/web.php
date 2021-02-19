<?php

use App\Http\Controllers\ExpensesController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['middleware'=>'auth:sanctum'], function (){
   Route::resource('expenses', ExpensesController::class);
   Route::get('expenses/{id}/cancel', [ExpensesController::class, 'cancel'])->name('expenses.cancel');
   Route::get('expenses/{id}/approve', [ExpensesController::class, 'approve'])->name('expenses.approve');
   Route::get('expenses/{id}/reject', [ExpensesController::class, 'reject'])->name('expenses.reject');
});

