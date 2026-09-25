<?php

use App\Http\Controllers\DocController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/documents/{document}/download', [DocController::class, 'download'])->name('documents.download');

