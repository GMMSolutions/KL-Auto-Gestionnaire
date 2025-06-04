<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
            $vin = strtoupper($request->chassis_number);
            $url = "{$apiPrefix}/{$apiKey}/decode/{$vin}.json";

            //alert the url on the console
            echo $url;

            $response = Http::get($url);

            Log::info('API Response Status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                $brand = collect($data['decode'] ?? [])
                    ->firstWhere('label', 'Make')['value'] ?? '';
                $type = collect($data['decode'] ?? [])
                    ->firstWhere('label', 'Model')['value'] ?? '';

                return response()->json([
                    'success' => true,
                    'data' => [
                        'brand' => $brand,
                        'type' => $type
                    ]
                ]);
            }

            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? 'Erreur API non spécifiée';

            return response()->json([
                'success' => false,
                'message' => "Erreur API: {$errorMessage} (Status: {$response->status()})"
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('VIN API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la communication avec le service VIN : ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Buyer
            'buyer_name' => 'required|string|max:255',
            'buyer_surname' => 'required|string|max:255',
            'buyer_birth_date' => 'required|date',
            'buyer_address' => 'required|string|max:255',
            'buyer_zip' => 'required|string|max:10',
            'buyer_city' => 'required|string|max:100',
            'buyer_phone' => 'required|string|max:20',
            'buyer_email' => 'required|email|max:255',

            // Vehicle
            'vehicle_brand' => 'required|string|max:100',
            'vehicle_type' => 'required|string|max:100',
            'first_registration_date' => 'required|date',
            'mileage' => 'required|integer|min:0',
            'chassis_number' => 'required|string|size:17|unique:contracts,chassis_number',
            'color' => 'required|string|max:50',
            'plate_number' => 'required|string|max:20',
            'has_accident' => 'boolean',

            // Sale
            'sale_price' => 'nullable|numeric|min:0',
            'expertise_date' => 'nullable|date',
            'deposit' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
            'payment_condition' => 'nullable|in:cash,leasing,credit',
            'warranty' => 'nullable|in:no_warranty,quality_1_qbase,quality_1_q3,quality_1_q5',
            'warranty_amount' => 'nullable|required_if:warranty,quality_1_q5|numeric|min:0',
        ]);

        // Calcul automatique du restant dû
        if (!isset($validated['remaining_amount']) && isset($validated['sale_price'], $validated['deposit'])) {
            $validated['remaining_amount'] = $validated['sale_price'] - $validated['deposit'];
        }

        $contract = Contract::create($validated);

        return redirect()->route('contracts.show', $contract->id)
            ->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }
}