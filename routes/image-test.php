<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestImagesController;

// Routes for image testing
Route::get('/test-images', [TestImagesController::class, 'index'])->name('test-images');
Route::post('/test-images/upload', [TestImagesController::class, 'upload'])->name('test-images.upload');
Route::post('/test-images/regenerate', [TestImagesController::class, 'regenerateReports'])->name('test-images.regenerate');
