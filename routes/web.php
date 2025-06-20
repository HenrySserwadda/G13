<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RawMaterialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function(){
    return view('about');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\ChatController;

Route::get('/chat', [ChatController::class, 'index'])->middleware('auth');
Route::resource('products', ProductController::class);
Route::middleware(['auth'])->group(function () {
    // Raw Materials Routes
    Route::resource('raw-materials', RawMaterialController::class);
    Route::post('raw-materials/{rawMaterial}/stock-adjustment', [RawMaterialController::class, 'stockAdjustment'])
        ->name('raw-materials.stock-adjustment');
});
