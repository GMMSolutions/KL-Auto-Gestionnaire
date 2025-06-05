<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContractController;

// Page d'accueil (dashboard)
Route::get('/', [DashboardController::class, 'index'])->name('home');

// Public welcome page (if needed)
Route::get('/welcome', function () {
    return view('welcome');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Contract routes
    Route::resource('contracts', ContractController::class)->except(['create', 'edit', 'update', 'destroy']);
    Route::get('/contracts/create-sale', [ContractController::class, 'createSale'])->name('contracts.create-sale');
    Route::get('/contracts/create-purchase', [ContractController::class, 'createPurchase'])->name('contracts.create-purchase');
    Route::post('/api/getVehicleInfo', [ContractController::class, 'getVehicleInfo'])->name('contracts.vehicle.info');
    
    // Ajoutez ici vos autres routes protégées
    // Exemple : Route::resource('clients', ClientController::class);
});
