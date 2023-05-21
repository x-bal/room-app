<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\ExtraChangeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('extra-change/get', [ExtraChangeController::class, 'get'])->name('extra-change.list');
    Route::get('room/get', [RoomController::class, 'get'])->name('room.list');

    Route::middleware('isAdmin')->group(function () {
        Route::get('users/get', [UserController::class, 'get'])->name('users.list');
        Route::resource('users', UserController::class);

        Route::get('type-room/get', [RoomTypeController::class, 'get'])->name('type-room.list');
        Route::resource('type-room', RoomTypeController::class);

        Route::resource('extra-change', ExtraChangeController::class);

        Route::resource('room', RoomController::class);
    });

    Route::get('transaction/get', [TransactionController::class, 'get'])->name('transaction.list');
    Route::get('transaction/get-room/{transaction:id}', [TransactionController::class, 'getRoom'])->name('transaction.list-room');
    Route::post('/transaction/room/{transaction:id}', [TransactionController::class, 'room'])->name('transaction.room');
    Route::delete('/transaction/room/{room:id}', [TransactionController::class, 'deleteRoom'])->name('transaction.delete-room');
    Route::post('/transaction/extra-change/{room:id}', [TransactionController::class, 'extra'])->name('transaction.extra');
    Route::resource('transaction', TransactionController::class);

    Route::get('detail-transaction/get/{id}', [DetailTransactionController::class, 'get'])->name('detail-transaction.list');
    Route::resource('detail-transaction', DetailTransactionController::class);
});
