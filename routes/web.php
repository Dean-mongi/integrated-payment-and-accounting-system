<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/ledger', [DashboardController::class, 'ledger'])->name('ledger');
Route::get('/reconciliation', [DashboardController::class, 'reconciliation'])->name('reconciliation');
Route::get('/fees', [DashboardController::class, 'fees'])->name('fees');
Route::get('/currency', [DashboardController::class, 'currency'])->name('currency');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::post('/reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store');
