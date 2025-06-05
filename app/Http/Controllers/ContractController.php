<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::latest()->paginate(10);
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        // Redirect to createsale for backward compatibility
        return redirect()->route('contracts.createsale');
    }

    public function createSale()
    {
        return view('contracts.createsales');
    }

    public function createPurchase()
    {
        return view('contracts.createpurchase');
    }

    public function getVehicleInfo(Request $request)
    {
        $request->validate([
            'chassis_number' => 'required|string|size:17'
        ]);

        $vin = mb_strtoupper($request->input('chassis_number'));

        $apiPrefix = "https://api.vindecoder.eu/3.2";
        $apiKey = "49cc4c9b0533";
        $secretKey = "df90e9ea6c";
        $id = "decode";

        $controlSum = substr(sha1("{$vin}|{$id}|{$apiKey}|{$secretKey}"), 0, 10);

        try {
            $response = Http::get("{$apiPrefix}/{$apiKey}/{$controlSum}/decode/{$vin}.json");

            if ($response->successful()) {
                $result = $response->json();
                return response()->json($result);
            } else {
                Log::error("Erreur API Vincario : " . $response->body());
                return response()->json(['error' => 'Erreur lors de la récupération des infos véhicule.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Exception API Vincario : " . $e->getMessage());
            return response()->json(['error' => 'Service temporairement indisponible.'], 500);
        }
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            // Buyer - Required fields
            'buyer_name' => 'required|string|max:255',
            'buyer_surname' => 'required|string|max:255',
            'buyer_address' => 'required|string|max:255',
            'buyer_zip' => 'required|string|max:10',
            'buyer_city' => 'required|string|max:100',
            'buyer_phone' => 'required|string|max:20',
            
            // Buyer - Optional fields
            'buyer_birth_date' => 'nullable|date',
            'buyer_email' => 'nullable|email|max:255',

            // Vehicle - Required fields
            'vehicle_brand' => 'required|string|max:100',
            'vehicle_type' => 'required|string|max:100',
            'first_registration_date' => 'required|date',
            'mileage' => 'required|integer|min:0',
            'chassis_number' => 'required|string|size:17|unique:contracts,chassis_number',
            'color' => 'required|string|max:50',
            
            // Vehicle - Optional fields
            'plate_number' => 'nullable|string|max:20',
            'has_accident' => 'boolean',

            // Sale - Required fields
            'sale_price' => 'required|numeric|min:0',
            'payment_condition' => 'nullable|required_if:contract_type,vente|in:cash,leasing,credit',
            'warranty' => 'nullable|required_if:contract_type,vente|in:no_warranty,quality_1_qbase,quality_1_q3,quality_1_q5',
            
            // Sale - Optional fields
            'expertise_date' => 'nullable|date',
            'deposit' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
            'warranty_amount' => 'nullable|required_if:warranty,quality_1_q5|numeric|min:0',
        ]);

        // Calcul automatique du restant dû
        if (!isset($validated['remaining_amount']) && isset($validated['sale_price'], $validated['deposit'])) {
            $validated['remaining_amount'] = $validated['sale_price'] - $validated['deposit'];
        }

        Contract::create($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }
}