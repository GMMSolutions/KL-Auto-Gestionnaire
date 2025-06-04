<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        try {
            $apiPrefix = "https://api.vindecoder.eu/3.2";
            $apiKey = config('app.VIN_API_KEY');
            $secretKey = config('app.VIN_API_SECRET');
            $id = "decode";
            $vin = mb_strtoupper($request->chassis_number);
            
            // Generate control sum
            $controlSum = substr(sha1("$vin|$id|$apiKey|$secretKey"), 0, 10);
            
            // Build URL
            $url = "$apiPrefix/$apiKey/$controlSum/decode/$vin.json";
            
            // Log the URL for debugging
            \Log::info('VIN API Request URL: ' . $url);
            
            // Make API request
            try {
                $response = Http::get($url);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Log the API response
                    \Log::info('VIN API Response: ' . json_encode($data));
                    
                    // Extract brand and type from the decode array
                    $brand = '';
                    $type = '';
                    
                    foreach ($data['decode'] as $item) {
                        if ($item['label'] === 'Make') {
                            $brand = $item['value'];
                        } elseif ($item['label'] === 'Model') {
                            $type = $item['value'];
                        }
                    }
                    
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'brand' => $brand,
                            'type' => $type
                        ]
                    ]);
                } else {
                    // Log the error response
                    \Log::error('VIN API Error Response: ' . $response->body());
                    return response()->json([
                        'success' => false,
                        'message' => 'API returned error: ' . $response->status() . ' - ' . $response->body()
                    ], $response->status());
                }
            } catch (\Exception $e) {
                // Log the error
                \Log::error('VIN API Request Error: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('VIN API Error: ' . $e->getMessage());
            
            // Return more specific error message
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la communication avec le service VIN: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not retrieve vehicle information. Please check the chassis number.'
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
