<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleRepairController extends Controller
{
    /**
     * Display a listing of repairs for a vehicle.
     */
    public function index(Vehicle $vehicle)
    {
        $repairs = $vehicle->repairs()->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $repairs
        ]);
    }

    /**
     * Store a newly created repair for a vehicle.
     */
    public function store(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0|max:9999999.99',
        ]);

        try {
            DB::beginTransaction();
            
            $repair = $vehicle->repairs()->create([
                'description' => $validated['description'],
                'amount' => $validated['amount'],
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Réparation ajoutée avec succès',
                'data' => $repair
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding repair: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'ajout de la réparation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified repair.
     */
    public function show(Vehicle $vehicle, Repair $repair)
    {
        if ($repair->vehicle_id !== $vehicle->id) {
            return response()->json([
                'success' => false,
                'message' => 'Réparation non trouvée pour ce véhicule'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $repair
        ]);
    }

    /**
     * Remove the specified repair from storage.
     */
    public function destroy(Vehicle $vehicle, Repair $repair)
    {
        if ($repair->vehicle_id !== $vehicle->id) {
            return response()->json([
                'success' => false,
                'message' => 'Réparation non trouvée pour ce véhicule'
            ], 404);
        }
        
        try {
            $repair->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Réparation supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting repair: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de la réparation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export repairs for a vehicle as PDF
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Barryvdh\DomPDF\PDF
     */
    public function exportPdf(Vehicle $vehicle)
    {
        $repairs = $vehicle->repairs()->latest()->get();
        $total = $repairs->sum('amount');
        
        $pdf = PDF::loadView('contracts.templates.repairs', [
            'vehicle' => $vehicle,
            'repairs' => $repairs,
            'total' => $total,
            'date' => now()->format('d.m.Y')
        ]);
        
        return $pdf->stream("reparations-{$vehicle->id}-{$vehicle->vehicle_brand}-{$vehicle->vehicle_type}.pdf");
    }
}
