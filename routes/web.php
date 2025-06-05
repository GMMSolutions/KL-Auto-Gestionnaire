<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContractController;

// Page d'accueil (contrats)
Route::get('/', [ContractController::class, 'index'])->name('home');

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
    // Contract custom routes — placer AVANT le resource
    Route::get('/contracts/createsale', [ContractController::class, 'createSale'])->name('contracts.createsale');
    Route::get('/contracts/createpurchase', [ContractController::class, 'createPurchase'])->name('contracts.createpurchase');
    Route::post('/api/getVehicleInfo', [ContractController::class, 'getVehicleInfo'])->name('contracts.vehicle.info');

    // Edit routes
    Route::get('/contracts/{contract}/editsale', [ContractController::class, 'editSale'])->name('contracts.editsale');
    Route::get('/contracts/{contract}/editpurchase', [ContractController::class, 'editPurchase'])->name('contracts.editpurchase');

    // Resource route ensuite
    Route::resource('contracts', ContractController::class)->except(['create', 'edit', 'update']);
    
    // Update route
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    
    // Specific delete route for better confirmation handling
    Route::delete('/contracts/{contract}/delete', [ContractController::class, 'destroy'])->name('contracts.destroy');
    
    // PDF Generation Routes
    Route::get('/contracts/{contract}/pdf', [ContractController::class, 'generatePdf'])->name('contracts.pdf');
    Route::get('/contracts/{contract}/download', [ContractController::class, 'downloadPdf'])->name('contracts.download');
});

