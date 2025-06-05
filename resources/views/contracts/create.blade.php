@extends('layouts.app')

@section('title', 'Nouveau Contrat')

@push('styles')
    <!-- Add meta tags for API configuration -->
    <meta name="vin-api-key" content="{{ config('app.VIN_API_KEY') }}">
    <meta name="vin-api-secret" content="{{ config('app.VIN_API_SECRET') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nouveau Contrat de Vente</h4>
                </div>

                <!-- Step Indicator -->
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4 position-relative">
                        <div class="position-absolute w-100" style="top: 20px; z-index: -1;">
                            <hr class="m-0">
                        </div>
                        <div class="d-flex flex-column align-items-center" id="step1-indicator">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">1</div>
                            <div>Véhicule</div>
                        </div>
                        <div class="d-flex flex-column align-items-center" id="step2-indicator">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">2</div>
                            <div>Acheteur</div>
                        </div>
                        <div class="d-flex flex-column align-items-center" id="step3-indicator">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">3</div>
                            <div>Vente</div>
                        </div>
                    </div>

                    <!-- Step 1: Vehicle Information -->
                    <form id="contractForm" action="{{ route('contracts.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div id="step1" class="active">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">1. Informations du véhicule</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Commencez par entrer le numéro de châssis pour récupérer les informations du véhicule.
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label for="chassis_number" class="form-label">Numéro de châssis (VIN)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="chassis_number" 
                                                       placeholder="Ex: WBA8E5G50JNU12345" required>
                                                <button class="btn btn-outline-secondary" type="button" id="searchVehicle">
                                                    <i class="fas fa-search"></i> Rechercher
                                                </button>
                                            </div>
                                            <div class="form-text">17 caractères alphanumériques</div>
                                        </div>
                                    </div>

                                    <div id="vehicleInfo" class="d-none">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="vehicle_brand" class="form-label">Marque</label>
                                                <input type="text" class="form-control" id="vehicle_brand" name="vehicle_brand" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="vehicle_type" class="form-label">Type</label>
                                                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="first_registration_date" class="form-label">Première immatriculation</label>
                                                <input type="date" class="form-control" id="first_registration_date" name="first_registration_date" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="mileage" class="form-label">Kilométrage</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="mileage" name="mileage" min="0" required>
                                                    <span class="input-group-text">km</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="color" class="form-label">Couleur</label>
                                                <input type="text" class="form-control" id="color" name="color" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="plate_number" class="form-label">Plaques d'immatriculation</label>
                                                <input type="text" class="form-control" id="plate_number" name="plate_number" required>
                                            </div>
                                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="has_accident" name="has_accident">
                                                    <label class="form-check-label" for="has_accident">
                                                        Véhicule accidenté
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="button" class="btn btn-primary" id="nextToStep2" data-step="2" disabled>
                                        Suivant <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Buyer Information -->
                        <div id="step2" class="d-none">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">2. Informations de l'acheteur</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_lastname" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="buyer_lastname" name="buyer_lastname" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_firstname" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="buyer_firstname" name="buyer_firstname" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="buyer_birth_date" class="form-label">Date de naissance</label>
                                            <input type="date" class="form-control" id="buyer_birth_date" name="buyer_birth_date" required>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="buyer_address" class="form-label">Adresse</label>
                                            <input type="text" class="form-control" id="buyer_address" name="buyer_address" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="buyer_zip" class="form-label">Code postal</label>
                                            <input type="text" class="form-control" id="buyer_zip" name="buyer_zip" required>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="buyer_city" class="form-label">Ville</label>
                                            <input type="text" class="form-control" id="buyer_city" name="buyer_city" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_phone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="buyer_phone" name="buyer_phone" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="buyer_email" name="buyer_email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="button" class="btn btn-secondary me-2" data-step="1">
                                        <i class="fas fa-arrow-left me-2"></i>Précédent
                                    </button>
                                    <button type="button" class="btn btn-primary" data-step="3">
                                        Suivant <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Sale Information -->
                        <div id="step3" class="d-none">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">3. Informations de vente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="sale_price" class="form-label">Prix de vente (TTC)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">€</span>
                                                <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expertise_date" class="form-label">Date d'expertise</label>
                                            <input type="date" class="form-control" id="expertise_date" name="expertise_date" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="deposit" class="form-label">Accompte</label>
                                            <div class="input-group">
                                                <span class="input-group-text">€</span>
                                                <input type="number" step="0.01" class="form-control" id="deposit" name="deposit" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="remaining_amount" class="form-label">Reste à payer</label>
                                            <div class="input-group">
                                                <span class="input-group-text">€</span>
                                                <input type="number" step="0.01" class="form-control" id="remaining_amount" name="remaining_amount" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="payment_condition" class="form-label">Conditions de paiement</label>
                                            <select class="form-select" id="payment_condition" name="payment_condition" required>
                                                <option value="">Sélectionnez...</option>
                                                <option value="cash">Comptant</option>
                                                <option value="leasing">Leasing</option>
                                                <option value="credit">Crédit</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="warranty" class="form-label">Garantie</label>
                                            <select class="form-select" id="warranty" name="warranty" required>
                                                <option value="">Sélectionnez...</option>
                                                <option value="no_warranty">Sans garantie (export)</option>
                                                <option value="quality_1_qbase">Garantie Quality 1 QBase</option>
                                                <option value="quality_1_q3">Garantie Quality 1 Q3</option>
                                                <option value="quality_1_q5">Garantie Quality 1 Q5</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3" id="warrantyAmountSection" style="display: none;">
                                            <label for="warranty_amount" class="form-label">Montant supplémentaire pour Q5</label>
                                            <div class="input-group">
                                                <span class="input-group-text">€</span>
                                                <input type="number" step="0.01" class="form-control" id="warranty_amount" name="warranty_amount">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="button" class="btn btn-secondary me-2" data-step="2">
                                        <i class="fas fa-arrow-left me-2"></i>Précédent
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Enregistrer le contrat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize datepickers
    flatpickr('.datepicker', {
        locale: 'fr',
        dateFormat: 'Y-m-d'
    });

    // Vehicle search functionality
    const searchVehicleBtn = document.getElementById('searchVehicle');
    const chassisNumberInput = document.getElementById('chassis_number');
    const vehicleInfoSection = document.getElementById('vehicleInfo');
    const nextToStep2Btn = document.getElementById('nextToStep2');

    searchVehicleBtn.addEventListener('click', function() {
        const chassisNumber = chassisNumberInput.value;
        
        // Reset marque and type fields
        document.getElementById('vehicle_brand').value = '';
        document.getElementById('vehicle_type').value = '';
        
        if (chassisNumber.length !== 17) {
            alert('Le numéro de châssis doit contenir exactement 17 caractères.');
            return;
        }

        // Show loading state
        searchVehicleBtn.disabled = true;
        searchVehicleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';

        // Make API request
        fetch('{{ route('contracts.vehicle.info') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                chassis_number: chassisNumber
            })
        })
        .then(response => {
            console.log('API Response Headers:', response.headers);
            console.log('API Response Status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API Response Data:', data);
            
            // Find brand and model in the decode array
            let brand = '';
            let type = '';
            
            if (data.decode && Array.isArray(data.decode)) {
                data.decode.forEach(item => {
                    if (item.label === 'Make') {
                        brand = item.value;
                    } else if (item.label === 'Model') {
                        type = item.value;
                    }
                });
            }
            
            // Populate fields based on what we found
            if (data.decode && Array.isArray(data.decode)) {
                data.decode.forEach(item => {
                    if (item.label === 'Make') {
                        document.getElementById('vehicle_brand').value = item.value;
                    } else if (item.label === 'Model') {
                        document.getElementById('vehicle_type').value = item.value;
                    }
                });
                
                // Show vehicle info section and enable next button
                vehicleInfoSection.style.display = 'block';
                nextToStep2Btn.disabled = false;
            } else {
                alert('Erreur: Impossible de récupérer les informations du véhicule.');
            }
        })
        .catch(error => {
            alert('Erreur lors de la communication avec le serveur.');
        })
        .finally(() => {
            // Reset button state
            searchVehicleBtn.disabled = false;
            searchVehicleBtn.innerHTML = '<i class="fas fa-search"></i> Rechercher';
        });
    });

    // Step navigation
    function updateStepIndicator(currentStep) {
        const steps = document.querySelectorAll('.step');
        steps.forEach((step, index) => {
            if (index < currentStep) {
                step.classList.add('completed');
            } else if (index === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }

    function showStep(stepNumber) {
        // Validate required fields before proceeding
        if (stepNumber === 2) {
            // Validate vehicle info
            const brand = document.getElementById('vehicle_brand').value.trim();
            const type = document.getElementById('vehicle_type').value.trim();
            
            if (!brand || !type) {
                alert('Veuillez remplir les champs Marque et Type du véhicule.');
                return;
            }
        }

        // Validate buyer info before proceeding to sale info
        if (stepNumber === 3) {
            // Validate buyer info
            const buyerName = document.getElementById('buyer_name').value.trim();
            const buyerSurname = document.getElementById('buyer_surname').value.trim();
            const buyerBirthDate = document.getElementById('buyer_birth_date').value.trim();
            const buyerAddress = document.getElementById('buyer_address').value.trim();
            const buyerZip = document.getElementById('buyer_zip').value.trim();
            const buyerCity = document.getElementById('buyer_city').value.trim();
            const buyerPhone = document.getElementById('buyer_phone').value.trim();
            const buyerEmail = document.getElementById('buyer_email').value.trim();
            
            if (!buyerName || !buyerSurname || !buyerBirthDate || !buyerAddress || !buyerZip || !buyerCity || !buyerPhone || !buyerEmail) {
                alert('Veuillez remplir tous les champs du formulaire acheteur.');
                return;
            }
        }

        // Hide all steps
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
        });

        // Show selected step
        const step = document.getElementById('step' + stepNumber);
        if (step) {
            step.classList.add('active');
            step.style.display = 'block';
        }

        // Update step indicator
        updateStepIndicator(stepNumber - 1);
    }

    // Navigation buttons event listeners
    document.querySelectorAll('[data-step]').forEach(button => {
        button.addEventListener('click', function() {
            const targetStep = parseInt(this.getAttribute('data-step'));
            showStep(targetStep);
        });
    });
    
    // Initialize first step
    updateStepIndicator(1);

    // Calculate remaining amount when deposit changes
    const salePriceInput = document.getElementById('sale_price');
    const depositInput = document.getElementById('deposit');
    const remainingAmountInput = document.getElementById('remaining_amount');

    function calculateRemainingAmount() {
        const salePrice = parseFloat(salePriceInput.value) || 0;
        const deposit = parseFloat(depositInput.value) || 0;
        remainingAmountInput.value = (salePrice - deposit).toFixed(2);
    }

    salePriceInput.addEventListener('input', calculateRemainingAmount);
    depositInput.addEventListener('input', calculateRemainingAmount);

    // Warranty amount section visibility
    const warrantySelect = document.getElementById('warranty');
    const warrantyAmountSection = document.getElementById('warrantyAmountSection');
    warrantySelect.addEventListener('change', function() {
        warrantyAmountSection.style.display = this.value === 'quality_1_q5' ? 'block' : 'none';
    });
});
</script>
@endpush
@endsection
