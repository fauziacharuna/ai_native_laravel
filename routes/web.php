<?php

use App\Livewire\AmbilAntrian;
use App\Livewire\DisplayAntrian;
use App\Livewire\LandingPage;
use App\Livewire\LayananPage;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ReportController;

// Route::get('/', function () {
//     return redirect()->route('tracking.index');
// });

Route::get('/ambil-antrian', AmbilAntrian::class)
    ->name('ambil-antrian');

Route::get('/display-antrian', DisplayAntrian::class)
    ->name('display-antrian');

Route::get('/', LandingPage::class);
Route::get('/layanan', LayananPage::class)->name('layanan');
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::get('/tracking/search', [TrackingController::class, 'search'])->name('tracking.search');
Route::get('/survey-kepuasan', \App\Livewire\SurveiPage::class)->name('survey.kepuasan');

Route::get('/admin/reports/download', [ReportController::class, 'download'])->name('reports.download');
Route::get('/admin/reports/download-excel', [ReportController::class, 'downloadExcel'])->name('reports.download.excel');
Route::get('/antrian/{antrian}/ticket', [\App\Http\Controllers\AntrianTicketController::class, 'show'])->name('antrian.ticket');
