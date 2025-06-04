<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContractController extends Controller
{
    public function create()
    {
        return view('contracts.create');
    }

    public function getVehicleInfo(Request $request)
    {
        $request->validate([
            'chassis_number' => 'required|string|size:17'
        ]);

        // This is a placeholder for the actual API call
        // The client will need to implement the actual API integration
        $response = Http::get('https://api.vehicle-info.example.com/vehicles', [
            'chassis' => $request->chassis_number,
            // Add any required API keys or parameters here
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'brand' => $response->json('brand', ''),
                    'type' => $response->json('type', '')
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not retrieve vehicle information.'
        ], 400);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Buyer Information
            'buyer_name' => 'required|string|max:255',
            'buyer_surname' => 'required|string|max:255',
            'buyer_birth_date' => 'required|date',
            'buyer_address' => 'required|string|max:255',
            'buyer_zip' => 'required|string|max:10',
            'buyer_city' => 'required|string|max:100',
            'buyer_phone' => 'required|string|max:20',
            'buyer_email' => 'required|email|max:255',
            
            // Vehicle Information
            'vehicle_brand' => 'required|string|max:100',
            'vehicle_type' => 'required|string|max:100',
            'first_registration_date' => 'required|date',
            'mileage' => 'required|integer|min:0',
            'chassis_number' => 'required|string|size:17|unique:contracts,chassis_number',
            'color' => 'required|string|max:50',
            'plate_number' => 'required|string|max:20',
            'has_accident' => 'boolean',
            
            // Sale Information
            'sale_price' => 'nullable|numeric|min:0',
            'expertise_date' => 'nullable|date',
            'deposit' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
            'payment_condition' => 'nullable|in:cash,leasing,credit',
            'warranty' => 'nullable|in:no_warranty,quality_1_qbase,quality_1_q3,quality_1_q5',
            'warranty_amount' => 'nullable|required_if:warranty,quality_1_q5|numeric|min:0',
        ]);

        // Calculate remaining amount if not provided
        if (!isset($validated['remaining_amount']) && isset($validated['sale_price'], $validated['deposit'])) {
            $validated['remaining_amount'] = $validated['sale_price'] - $validated['deposit'];
        }

        $contract = Contract::create($validated);

        return redirect()->route('contracts.show', $contract->id)
            ->with('success', 'Contract created successfully.');
    }

    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }
}
