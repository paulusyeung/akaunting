<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin/double-entry',
    'middleware' => 'admin',
    'namespace' => 'Modules\DoubleEntry\Http\Controllers',
], function () {
    Route::get('/', 'Landing@index')->name('double-entry.index');

    Route::resource('accounts', 'Accounts')->names('double-entry.accounts');
    Route::resource('journals', 'Journals')->names('double-entry.journals');

    Route::get('reports/ledger', 'Reports@ledger')->name('double-entry.reports.ledger');
    Route::get('reports/trial-balance', 'Reports@trialBalance')->name('double-entry.reports.trial-balance');
    Route::get('reports/income-statement', 'Reports@incomeStatement')->name('double-entry.reports.income-statement');
    Route::get('reports/balance-sheet', 'Reports@balanceSheet')->name('double-entry.reports.balance-sheet');
    Route::get('reports/export-ledger', 'Reports@exportLedger')->name('double-entry.reports.export-ledger');
});
