<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('landingpage');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/assets/explore', [AssetController::class, 'explore'])->name('asset.explore');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/assets', [AssetController::class, 'index'])->name('asset.index');

    Route::get('/assets/create', [AssetController::class, 'create'])->name('asset.create');
    
    Route::post('/assets/store', [AssetController::class, 'store'])->name('asset.store');
    Route::post('/assets/update', [AssetController::class, 'update'])->name('asset.update');

    Route::get('/assets/edit/{id}', [AssetController::class, 'edit'])->name('asset.edit');
    Route::get('/assets/{id}', [AssetController::class, 'show'])->name('asset.show');
    Route::get('/assets/delete/{id}', [AssetController::class, 'delete'])->name('asset.delete');
    Route::delete('/assets/delete/{id}', [AssetController::class, 'destroy'])->name('asset.destroy');
});

Route::get('/assets/explore/share/asset/item={id}', [AssetController::class, 'explore_show'])->name('asset.explore_show');

Route::middleware('auth')->group(function () {
    Route::get('/tags', [TagController::class, 'index'])->name('tag.index');
});

Route::get('/legal/{document}/{locale?}', function (string $document, string $locale = 'en') {
    return view("legal.$locale.$document", compact('document', 'locale'));
})
    ->whereIn('document', ['privacy', 'notice', 'terms'])
    ->whereIn('locale', ['en', 'de', 'hu'])
    ->name('legal.show');

require __DIR__.'/auth.php';
