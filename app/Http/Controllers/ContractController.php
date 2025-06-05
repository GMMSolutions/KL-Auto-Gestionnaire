<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ContractController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Generate a PDF for the specified contract.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(Contract $contract)
    {
        if (!in_array($contract->contract_type, ['vente', 'achat'])) {
            abort(404, 'Type de contrat non valide');
        }

        $template = 'contracts.templates.' . $contract->contract_type;
        $pdf = PDF::loadView($template, compact('contract'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Set the filename
        $filename = 'contrat-' . $contract->contract_type . '-' . $contract->id . '.pdf';
        
        return $pdf->stream($filename);
    }
    
    /**
     * Download the PDF for the specified contract.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadPdf(Contract $contract)
    {
        if (!in_array($contract->contract_type, ['vente', 'achat'])) {
            abort(404, 'Type de contrat non valide');
        }

        $template = 'contracts.templates.' . $contract->contract_type;
        $pdf = PDF::loadView($template, compact('contract'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Set the filename
        $filename = 'contrat-' . $contract->contract_type . '-' . $contract->id . '.pdf';
        
        return $pdf->download($filename);
    }

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
        $rules = [
            // Contract type
            'contract_type' => 'required|in:vente,achat',
            
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
            'chassis_number' => 'required|string|size:17',
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
        ];

        $validated = $request->validate($rules);

        // Set default values for purchase contracts
        if ($validated['contract_type'] === 'achat') {
            $validated['payment_condition'] = null;
            $validated['warranty'] = null;
            $validated['warranty_amount'] = null;
        }

        // Calculate remaining amount if not provided
        if (!isset($validated['remaining_amount']) && isset($validated['sale_price'], $validated['deposit'])) {
            $validated['remaining_amount'] = $validated['sale_price'] - $validated['deposit'];
        }

        // Create the contract with all validated data
        Contract::create($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Remove the specified contract from storage.
     */
    public function editSale(Contract $contract)
    {
        if ($contract->contract_type !== 'vente') {
            abort(404);
        }
        
        // Ensure dates are in the correct format for the view
        if ($contract->buyer_birth_date) {
            $contract->buyer_birth_date = \Carbon\Carbon::parse($contract->buyer_birth_date)->format('Y-m-d');
        }
        if ($contract->expertise_date) {
            $contract->expertise_date = \Carbon\Carbon::parse($contract->expertise_date)->format('Y-m-d');
        }
        if ($contract->first_registration_date) {
            $contract->first_registration_date = \Carbon\Carbon::parse($contract->first_registration_date)->format('Y-m-d');
        }
        
        return view('contracts.editsales', compact('contract'));
    }

    public function editPurchase(Contract $contract)
    {
        if ($contract->contract_type !== 'achat') {
            abort(404);
        }
        
        // Ensure dates are in the correct format for the view
        if ($contract->buyer_birth_date) {
            $contract->buyer_birth_date = \Carbon\Carbon::parse($contract->buyer_birth_date)->format('Y-m-d');
        }
        if ($contract->expertise_date) {
            $contract->expertise_date = \Carbon\Carbon::parse($contract->expertise_date)->format('Y-m-d');
        }
        if ($contract->first_registration_date) {
            $contract->first_registration_date = \Carbon\Carbon::parse($contract->first_registration_date)->format('Y-m-d');
        }
        
        return view('contracts.editpurchase', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $rules = [
            // Contract type
            'contract_type' => 'required|in:vente,achat',
            
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
            'chassis_number' => 'required|string|size:17',
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
        ];

        $validated = $request->validate($rules);

        // Set default values for purchase contracts
        if ($validated['contract_type'] === 'achat') {
            $validated['payment_condition'] = null;
            $validated['warranty'] = null;
            $validated['warranty_amount'] = null;
        }

        // Calculate remaining amount if not provided
        if (!isset($validated['remaining_amount']) && isset($validated['sale_price'], $validated['deposit'])) {
            $validated['remaining_amount'] = $validated['sale_price'] - $validated['deposit'];
        }

        // Update the contract with all validated data
        $contract->update($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contrat mis à jour avec succès.');
    }

    public function destroy(Contract $contract)
    {
        try {
            $contract->delete();
            return response()->json([
                'success' => true,
                'message' => 'Le contrat a été supprimé avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du contrat : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du contrat.'
            ], 500);
        }
    }
}