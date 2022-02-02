<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
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
    return view('home');
})->middleware(['client']);

Route::namespace('clients')->name('clients.')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('index')->middleware(['admin']);
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('show')->middleware(['admin']);;
    Route::get('/balance/{id?}', [ClientController::class, 'getBalance'])->name('getBalance')->middleware(['client']);;
    Route::get('/get/clients', [ClientController::class, 'getClients'])->name('getClients')->middleware(['client']);;
    Route::get('/get/client/{id}', [ClientController::class, 'getClient'])->name('getClient')->middleware(['client']);;
    Route::post('/clients', [ClientController::class, 'store'])->name('store')->middleware(['admin']);;
    Route::put('/clients', [ClientController::class, 'addBalance'])->name('add.balance')->middleware(['admin']);;
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('update')->middleware(['admin']);;
    Route::delete('/client/{id}', [ClientController::class, 'destroy'])->name('delete')->middleware(['admin']);;
});

Route::namespace('cards')->name('cards.')->group(function () {
    Route::get('/cards', [CardController::class, 'index'])->name('index')->middleware(['admin']);;
    Route::get('/cards/sum', [CardController::class, 'getSum'])->name('sum')->middleware(['admin']);;
    Route::get('/get/cards', function () {
        return view('cards.redeem');
    })->name('redeem')->middleware(['client']);;
    Route::get('/cards/all/{value?}', [CardController::class, 'getCards'])->name('getCards')->middleware(['client']);;
    Route::post('/cards', [CardController::class, 'store'])->name('store')->middleware(['admin']);;

    Route::delete('/cards/{id}', [CardController::class, 'destroy'])->name('delete')->middleware(['admin']);;
});

Route::namespace('reports')->name('reports.')->group(function () {
    Route::get('/reports', [ReportController::class, 'show'])->name('show')->middleware(['admin']);;
    Route::get('get/reports/{start_date?}/{end_date?}', [ReportController::class, 'getReport'])->name('getReport')->middleware(['admin']);;

    Route::post('/reports', [ReportController::class, 'store'])->name('store')->middleware(['client']);;
});

Auth::routes();

Route::get('/', function () {
    return view('master');
})->name('home')->middleware(['client']);
