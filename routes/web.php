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
    return view('master');
});

Route::namespace('clients')->name('clients.')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('index');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('show');
    Route::get('/balance/{id}', [ClientController::class, 'getBalance'])->name('getBalance');
    Route::get('/get/clients', [ClientController::class, 'getClients'])->name('getClients');
    Route::get('/get/client/{id}', [ClientController::class, 'getClient'])->name('getClient');
    Route::post('/clients', [ClientController::class, 'store'])->name('store');
    Route::put('/clients', [ClientController::class, 'addBalance'])->name('add.balance');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('update');
    Route::delete('/client/{id}', [ClientController::class, 'destroy'])->name('delete');
});

Route::namespace('cards')->name('cards.')->group(function () {
    Route::get('/cards', [CardController::class, 'index'])->name('index');
    Route::get('/cards/sum', [CardController::class, 'getSum'])->name('sum');
    Route::get('/get/cards', function () {
        return view('cards.redeem');
    })->name('redeem');
    Route::get('/cards/all/{value?}', [CardController::class, 'getCards'])->name('getCards');
    Route::post('/cards', [CardController::class, 'store'])->name('store');

    Route::delete('/cards/{id}', [CardController::class, 'destroy'])->name('delete');
});

Route::namespace('reports')->name('reports.')->group(function () {
    Route::get('/reports', [ReportController::class, 'show'])->name('show');
    Route::get('/reports/{start_date}/{end_date}', [ReportController::class, 'getReport'])->name('getReport');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('create');

    Route::post('/reports', [ReportController::class, 'store'])->name('store');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
