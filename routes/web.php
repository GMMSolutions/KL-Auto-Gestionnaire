<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

// Page d'accueil publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts/vehicle-info', [ContractController::class, 'getVehicleInfo'])->name('contracts.vehicle.info');
    Route::get('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    
    // Ajoutez ici vos autres routes protégées
    // Exemple : Route::resource('clients', ClientController::class);
});
