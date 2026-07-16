<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('tracking.index');
});

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::get('/tracking/search', [TrackingController::class, 'search'])->name('tracking.search');

Route::get('/admin/reports/download', [ReportController::class, 'download'])->name('reports.download');
