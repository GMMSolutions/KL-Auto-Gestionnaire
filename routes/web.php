<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\VehicleController;

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Routes protégées
Route::middleware('auth')->group(function () {
    // Page d'accueil (contrats)
    Route::get('/', [ContractController::class, 'index'])->name('home');
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
    
    // Facture Route
    Route::get('/contracts/{contract}/facture', [ContractController::class, 'viewFacture'])->name('contracts.facture');
    
    // Vehicles Routes
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    
    // Vehicle Repairs API Routes
    Route::prefix('vehicles/{vehicle}/repairs')->name('vehicles.repairs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\VehicleRepairController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\VehicleRepairController::class, 'store'])->name('store');
        Route::get('/{repair}', [\App\Http\Controllers\VehicleRepairController::class, 'show'])->name('show');
        Route::delete('/{repair}', [\App\Http\Controllers\VehicleRepairController::class, 'destroy'])->name('destroy');
        Route::get('/export-pdf', [\App\Http\Controllers\VehicleRepairController::class, 'exportPdf'])->name('export-pdf');
    });
});

