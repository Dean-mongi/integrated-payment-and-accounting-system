<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:admin,accountant');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics')->middleware('role:admin,accountant');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports')->middleware('role:admin,accountant');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings')->middleware('role:admin');
    Route::post('/settings/backup', [DashboardController::class, 'backup'])->name('settings.backup')->middleware('role:admin');

    Route::get('/ledger', [DashboardController::class, 'ledger'])->name('ledger')->middleware('role:admin,accountant');
    Route::get('/reconciliation', [DashboardController::class, 'reconciliation'])->name('reconciliation')->middleware('role:admin,accountant');
    Route::get('/fees', [DashboardController::class, 'fees'])->name('fees')->middleware('role:admin,accountant');
    Route::get('/currency', [DashboardController::class, 'currency'])->name('currency')->middleware('role:admin,accountant');

    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('invoice-payments.store')->middleware('role:admin,cashier,customer');
    Route::get('/receipts/{receipt}', [PaymentController::class, 'receipt'])->name('receipts.show');

    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store')->middleware('role:admin,accountant,cashier');
    Route::post('/reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store')->middleware('role:admin,accountant');
});
