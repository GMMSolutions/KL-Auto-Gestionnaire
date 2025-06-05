@extends('layouts.app')

@section('title', 'Nouveau Contrat')

@push('styles')
    <!-- Add meta tags for API configuration -->
    <meta name="vin-api-key" content="{{ config('app.VIN_API_KEY') }}">
    <meta name="vin-api-secret" content="{{ config('app.VIN_API_SECRET') }}">
    <style>
        .is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nouveau Contrat de Vente</h4>
                </div>

                <div class="card-body">
                    <form id="contractForm" action="{{ route('contracts.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Vehicle Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-car me-2"></i>1. Informations du véhicule</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Commencez par entrer le numéro de châssis pour récupérer les informations du véhicule.
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="chassis_number" class="form-label">Numéro de châssis (VIN)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('chassis_number') is-invalid @enderror" id="chassis_number" name="chassis_number" value="{{ old('chassis_number') }}" required>
                                            @error('chassis_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <button class="btn btn-outline-secondary" type="button" id="searchVehicle">
                                                <i class="fas fa-search"></i> Rechercher
                                            </button>
                                        </div>
                                        <div class="form-text">17 caractères alphanumériques</div>
                                    </div>
                                </div>

                                <div id="vehicleInfo">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="vehicle_brand" class="form-label">Marque <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('vehicle_brand') is-invalid @enderror" id="vehicle_brand" name="vehicle_brand" value="{{ old('vehicle_brand') }}" required>
                                            @error('vehicle_brand')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="vehicle_type" class="form-label">Type <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" required>
                                            @error('vehicle_type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_registration_date" class="form-label">Première immatriculation <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('first_registration_date') is-invalid @enderror" id="first_registration_date" name="first_registration_date" value="{{ old('first_registration_date') }}" required>
                                            @error('first_registration_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="mileage" class="form-label">Kilométrage <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('mileage') is-invalid @enderror" id="mileage" name="mileage" min="0" value="{{ old('mileage') }}" required>
                                                @error('mileage')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <span class="input-group-text">km</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="color" class="form-label">Couleur <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}" required>
                                            @error('color')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="plate_number" class="form-label">Plaques d'immatriculation</label>
                                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror" id="plate_number" name="plate_number" value="{{ old('plate_number') }}">
                                            @error('plate_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3 d-flex align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="hidden" name="has_accident" value="0">
                                                <input class="form-check-input" type="checkbox" id="has_accident" name="has_accident" value="1">
                                                <label class="form-check-label" for="has_accident">
                                                    Véhicule accidenté
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buyer Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>2. Informations de l'acheteur</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_surname" class="form-label">Nom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('buyer_surname') is-invalid @enderror" id="buyer_surname" name="buyer_surname" value="{{ old('buyer_surname') }}" required>
                                            @error('buyer_surname')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('buyer_name') is-invalid @enderror" id="buyer_name" name="buyer_name" value="{{ old('buyer_name') }}" required>
                                            @error('buyer_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                </div>
                                
                                <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="buyer_birth_date" class="form-label">Date de naissance</label>
                                            <input type="date" class="form-control @error('buyer_birth_date') is-invalid @enderror" id="buyer_birth_date" name="buyer_birth_date" value="{{ old('buyer_birth_date') }}">
                                            @error('buyer_birth_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="buyer_address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('buyer_address') is-invalid @enderror" id="buyer_address" name="buyer_address" value="{{ old('buyer_address') }}" required>
                                            @error('buyer_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                </div>
                                
                                <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="buyer_zip" class="form-label">Code postal <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('buyer_zip') is-invalid @enderror" id="buyer_zip" name="buyer_zip" value="{{ old('buyer_zip') }}" required>
                                            @error('buyer_zip')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="buyer_city" class="form-label">Ville <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('buyer_city') is-invalid @enderror" id="buyer_city" name="buyer_city" value="{{ old('buyer_city') }}" required>
                                            @error('buyer_city')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                </div>
                                
                                <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('buyer_phone') is-invalid @enderror" id="buyer_phone" name="buyer_phone" value="{{ old('buyer_phone') }}" required>
                                            @error('buyer_phone')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('buyer_email') is-invalid @enderror" id="buyer_email" name="buyer_email" value="{{ old('buyer_email') }}">
                                            @error('buyer_email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sale Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-euro-sign me-2"></i>3. Informations de vente</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="sale_price" class="form-label">Prix de vente (TTC)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" required>
                                            @error('sale_price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expertise_date" class="form-label">Date d'expertise</label>
                                        <input type="date" class="form-control @error('expertise_date') is-invalid @enderror" id="expertise_date" name="expertise_date" value="{{ old('expertise_date') }}" required>
                                        @error('expertise_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="deposit" class="form-label">Accompte</label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number" step="0.01" class="form-control @error('deposit') is-invalid @enderror" id="deposit" name="deposit" value="{{ old('deposit') }}" required>
                                            @error('deposit')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="remaining_amount" class="form-label">Reste à payer</label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number" step="0.01" class="form-control @error('remaining_amount') is-invalid @enderror" id="remaining_amount" name="remaining_amount" value="{{ old('remaining_amount') }}" readonly>
                                            @error('remaining_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="payment_condition" class="form-label">Conditions de paiement</label>
                                        <select class="form-select @error('payment_condition') is-invalid @enderror" id="payment_condition" name="payment_condition" required>
                                            @error('payment_condition')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
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
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Enregistrer le contrat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="buyer_surname" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('buyer_surname') is-invalid @enderror" id="buyer_surname" name="buyer_surname" value="{{ old('buyer_surname') }}" required>
                @error('buyer_surname')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="buyer_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('buyer_name') is-invalid @enderror" id="buyer_name" name="buyer_name" value="{{ old('buyer_name') }}" required>
                @error('buyer_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="buyer_birth_date" class="form-label">Date de naissance</label>
                <input type="date" class="form-control @error('buyer_birth_date') is-invalid @enderror" id="buyer_birth_date" name="buyer_birth_date" value="{{ old('buyer_birth_date') }}">
                @error('buyer_birth_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-8 mb-3">
                <label for="buyer_address" class="form-label">Adresse <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('buyer_address') is-invalid @enderror" id="buyer_address" name="buyer_address" value="{{ old('buyer_address') }}" required>
                @error('buyer_address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="buyer_zip" class="form-label">Code postal <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('buyer_zip') is-invalid @enderror" id="buyer_zip" name="buyer_zip" value="{{ old('buyer_zip') }}" required>
                @error('buyer_zip')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-8 mb-3">
                <label for="buyer_city" class="form-label">Ville <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('buyer_city') is-invalid @enderror" id="buyer_city" name="buyer_city" value="{{ old('buyer_city') }}" required>
                @error('buyer_city')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="buyer_phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control @error('buyer_phone') is-invalid @enderror" id="buyer_phone" name="buyer_phone" value="{{ old('buyer_phone') }}" required>
                @error('buyer_phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="buyer_email" class="form-label">Email</label>
                <input type="email" class="form-control @error('buyer_email') is-invalid @enderror" id="buyer_email" name="buyer_email" value="{{ old('buyer_email') }}">
                @error('buyer_email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Sale Information Section -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-euro-sign me-2"></i>3. Informations de vente</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="sale_price" class="form-label">Prix de vente (TTC)</label>
                <div class="input-group">
                    <span class="input-group-text">€</span>
                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" required>
                    @error('sale_price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="expertise_date" class="form-label">Date d'expertise</label>
                <input type="date" class="form-control @error('expertise_date') is-invalid @enderror" id="expertise_date" name="expertise_date" value="{{ old('expertise_date') }}" required>
                @error('expertise_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="deposit" class="form-label">Accompte</label>
                <div class="input-group">
                    <span class="input-group-text">€</span>
                    <input type="number" step="0.01" class="form-control @error('deposit') is-invalid @enderror" id="deposit" name="deposit" value="{{ old('deposit') }}" required>
                    @error('deposit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="remaining_amount" class="form-label">Reste à payer</label>
                <div class="input-group">
                    <span class="input-group-text">€</span>
                    <input type="number" step="0.01" class="form-control @error('remaining_amount') is-invalid @enderror" id="remaining_amount" name="remaining_amount" value="{{ old('remaining_amount') }}" readonly>
                    @error('remaining_amount')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="payment_condition" class="form-label">Conditions de paiement</label>
                <select class="form-select @error('payment_condition') is-invalid @enderror" id="payment_condition" name="payment_condition" required>
                    @error('payment_condition')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
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
</div>

<!-- Submit Button -->
<div class="text-center">
    <button type="submit" class="btn btn-success btn-lg">
        <i class="fas fa-save me-2"></i>Enregistrer le contrat
    </button>
</div>
</form>
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
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    
    // Add 'was-validated' class to form on submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }

    // Vehicle search functionality
    const searchVehicleBtn = document.getElementById('searchVehicle');
    const chassisNumberInput = document.getElementById('chassis_number');
    const vehicleBrandInput = document.getElementById('vehicle_brand');
    const vehicleTypeInput = document.getElementById('vehicle_type');

    if (searchVehicleBtn && chassisNumberInput) {
        searchVehicleBtn.addEventListener('click', function() {
            const chassisNumber = chassisNumberInput.value.trim();
            
            if (!chassisNumber) {
                chassisNumberInput.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Veuillez entrer un numéro de châssis.';
                
                // Remove any existing error message
                const existingError = chassisNumberInput.nextElementSibling;
                if (existingError && existingError.classList.contains('invalid-feedback')) {
                    existingError.remove();
                }
                
                // Add new error message
                chassisNumberInput.parentNode.insertBefore(errorDiv, chassisNumberInput.nextSibling);
                return;
            } else {
                chassisNumberInput.classList.remove('is-invalid');
                const existingError = chassisNumberInput.nextElementSibling;
                if (existingError && existingError.classList.contains('invalid-feedback')) {
                    existingError.remove();
                }
            }
            
            // Show loading state
            searchVehicleBtn.disabled = true;
            searchVehicleBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Recherche...';
            
            // Get CSRF token from meta tag
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make API request to your backend
            fetch('{{ route("contracts.get-vehicle-info") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    chassis_number: chassisNumber
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.decode) {
                    // Process the response data and fill the form
                    data.decode.forEach(item => {
                        if (item.label === 'Marque' && vehicleBrandInput) {
                            vehicleBrandInput.value = item.value || '';
                            vehicleBrandInput.dispatchEvent(new Event('input'));
                        } else if (item.label === 'Modèle' && vehicleTypeInput) {
                            vehicleTypeInput.value = item.value || '';
                            vehicleTypeInput.dispatchEvent(new Event('input'));
                        }
                    });
                } else {
                    throw new Error('No vehicle data found');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-3';
                errorDiv.textContent = 'Une erreur est survenue lors de la récupération des informations du véhicule.';
                
                // Remove any existing error message
                const existingAlert = document.querySelector('.alert.alert-danger');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                // Add new error message
                const formCard = document.querySelector('.card');
                if (formCard) {
                    formCard.parentNode.insertBefore(errorDiv, formCard.nextSibling);
                }
            })
            .finally(() => {
                // Reset button state
                searchVehicleBtn.disabled = false;
                searchVehicleBtn.innerHTML = '<i class="fas fa-search"></i> Rechercher';
            });
        });
    }

    // Calculate remaining amount when deposit changes
    const salePriceInput = document.getElementById('sale_price');
    const depositInput = document.getElementById('deposit');
    const remainingAmountInput = document.getElementById('remaining_amount');

    function calculateRemainingAmount() {
        if (salePriceInput && depositInput && remainingAmountInput) {
            const salePrice = parseFloat(salePriceInput.value) || 0;
            const deposit = parseFloat(depositInput.value) || 0;
            remainingAmountInput.value = (salePrice - deposit).toFixed(2);
            
            // Trigger change event on the remaining amount input
            const event = new Event('change');
            remainingAmountInput.dispatchEvent(event);
        }
    }

    if (salePriceInput) {
        salePriceInput.addEventListener('input', calculateRemainingAmount);
    }
    
    if (depositInput) {
        depositInput.addEventListener('input', calculateRemainingAmount);
    }

    // Warranty amount section visibility
    const warrantySelect = document.getElementById('warranty');
    const warrantyAmountSection = document.getElementById('warrantyAmountSection');
    
    function updateWarrantySection() {
        if (warrantySelect && warrantyAmountSection) {
            warrantyAmountSection.style.display = warrantySelect.value === 'quality_1_q5' ? 'block' : 'none';
        }
    }
    
    if (warrantySelect) {
        warrantySelect.addEventListener('change', updateWarrantySection);
        // Initialize on page load
        updateWarrantySection();
    }
});
</script>
@endpush
@endsection