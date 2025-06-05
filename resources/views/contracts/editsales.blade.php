@extends('layouts.app')

@section('title', 'Nouveau Contrat')

@push('styles')
<meta name="vin-api-key" content="{{ config('app.VIN_API_KEY') }}">
<meta name="vin-api-secret" content="{{ config('app.VIN_API_SECRET') }}">
<style>
    .field-group {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .field-group h5 {
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .btn-search {
        min-width: 120px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Modifier le Contrat de Vente</h4>
                </div>

                <div class="card-body">
                    <form id="contractForm" action="{{ route('contracts.update', $contract) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="contract_type" value="vente">
                        
                        <!-- Vehicle Information -->
                        <div class="field-group">
                            <h5><i class="fas fa-car me-2"></i>Informations du véhicule</h5>
                            
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Entrez le numéro de châssis pour récupérer automatiquement les informations du véhicule.
                            </div>
                            
                            <!-- VIN Search -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="chassis_number" class="form-label">Numéro de châssis (VIN) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control {{ $errors->has('chassis_number') ? 'is-invalid' : '' }}" 
                                               id="chassis_number" 
                                               name="chassis_number"
                                               value="{{ old('chassis_number', $contract->chassis_number) }}"
                                               placeholder="Ex: WBA8E5G50JNU12345" 
                                               maxlength="17"
                                               required>
                                        <button class="btn btn-outline-secondary btn-search" type="button" id="searchVehicle">
                                            <i class="fas fa-search"></i> Rechercher
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">17 caractères alphanumériques</small>
                                    @error('chassis_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vehicle_brand" class="form-label">Marque <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('vehicle_brand') ? 'is-invalid' : '' }}" 
                                           id="vehicle_brand" 
                                           name="vehicle_brand" 
                                           value="{{ old('vehicle_brand', $contract->vehicle_brand) }}" 
                                           required>
                                    @error('vehicle_brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="vehicle_type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('vehicle_type') ? 'is-invalid' : '' }}" 
                                           id="vehicle_type" 
                                           name="vehicle_type" 
                                           value="{{ old('vehicle_type', $contract->vehicle_type) }}" 
                                           required>
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="first_registration_date" class="form-label">Première immatriculation <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control {{ $errors->has('first_registration_date') ? 'is-invalid' : '' }}" 
                                           id="first_registration_date" 
                                           name="first_registration_date" 
                                           value="{{ old('first_registration_date', \Carbon\Carbon::parse($contract->first_registration_date)->format('Y-m-d')) }}" 
                                           required>
                                    @error('first_registration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mileage" class="form-label">Kilométrage <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control {{ $errors->has('mileage') ? 'is-invalid' : '' }}" 
                                               id="mileage" 
                                               name="mileage" 
                                               min="0" 
                                               value="{{ old('mileage', $contract->mileage) }}" 
                                               required>
                                        <span class="input-group-text">km</span>
                                    </div>
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="color" class="form-label">Couleur <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('color') ? 'is-invalid' : '' }}" 
                                           id="color" 
                                           name="color" 
                                           value="{{ old('color', $contract->color) }}" 
                                           required>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="plate_number" class="form-label">Plaques d'immatriculation</label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('plate_number') ? 'is-invalid' : '' }}" 
                                           id="plate_number" 
                                           name="plate_number"
                                           value="{{ old('plate_number', $contract->plate_number) }}">
                                    @error('plate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input type="hidden" name="has_accident" value="0">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="has_accident" 
                                               name="has_accident" 
                                               value="1"
                                               {{ old('has_accident', $contract->has_accident) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_accident">
                                            Véhicule accidenté
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buyer Information -->
                        <div class="field-group">
                            <h5><i class="fas fa-user me-2"></i>Informations de l'acheteur</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="buyer_surname" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('buyer_surname') ? 'is-invalid' : '' }}" 
                                           id="buyer_surname" 
                                           name="buyer_surname" 
                                           value="{{ old('buyer_surname', $contract->buyer_surname) }}" 
                                           required>
                                    @error('buyer_surname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="buyer_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('buyer_name') ? 'is-invalid' : '' }}" 
                                           id="buyer_name" 
                                           name="buyer_name" 
                                           value="{{ old('buyer_name', $contract->buyer_name) }}" 
                                           required>
                                    @error('buyer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="buyer_birth_date" class="form-label">Date de naissance</label>
                                        <input type="date" 
                                               class="form-control {{ $errors->has('buyer_birth_date') ? 'is-invalid' : '' }}" 
                                               id="buyer_birth_date" 
                                               name="buyer_birth_date"
                                               value="{{ old('buyer_birth_date', $contract->buyer_birth_date ? \Carbon\Carbon::parse($contract->buyer_birth_date)->format('Y-m-d') : '') }}">
                                        @error('buyer_birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                <div class="col-md-8 mb-3">
                                    <label for="buyer_address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('buyer_address') ? 'is-invalid' : '' }}" 
                                           id="buyer_address" 
                                           name="buyer_address" 
                                           value="{{ old('buyer_address', $contract->buyer_address) }}" 
                                           required>
                                    @error('buyer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="buyer_zip" class="form-label">Code postal <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('buyer_zip') ? 'is-invalid' : '' }}" 
                                           id="buyer_zip" 
                                           name="buyer_zip" 
                                           value="{{ old('buyer_zip', $contract->buyer_zip) }}" 
                                           required>
                                    @error('buyer_zip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="buyer_city" class="form-label">Ville <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control {{ $errors->has('buyer_city') ? 'is-invalid' : '' }}" 
                                           id="buyer_city" 
                                           name="buyer_city" 
                                           value="{{ old('buyer_city', $contract->buyer_city) }}" 
                                           required>
                                    @error('buyer_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="buyer_phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" 
                                           class="form-control {{ $errors->has('buyer_phone') ? 'is-invalid' : '' }}" 
                                           id="buyer_phone" 
                                           name="buyer_phone" 
                                           value="{{ old('buyer_phone', $contract->buyer_phone) }}" 
                                           required>
                                    @error('buyer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="buyer_email" class="form-label">Email</label>
                                    <input type="email" 
                                           class="form-control {{ $errors->has('buyer_email') ? 'is-invalid' : '' }}" 
                                           id="buyer_email" 
                                           name="buyer_email"
                                           value="{{ old('buyer_email', $contract->buyer_email) }}">
                                    @error('buyer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sale Information -->
                        <div class="field-group">
                            <h5><i class="fas fa-euro-sign me-2"></i>Informations de vente</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sale_price" class="form-label">Prix de vente (TTC) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control {{ $errors->has('sale_price') ? 'is-invalid' : '' }}" 
                                               id="sale_price" 
                                               name="sale_price" 
                                               value="{{ old('sale_price', $contract->sale_price) }}" 
                                               required>
                                    </div>
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="expertise_date" class="form-label">Date d'expertise</label>
                                    <input type="date" 
                                           class="form-control {{ $errors->has('expertise_date') ? 'is-invalid' : '' }}" 
                                           id="expertise_date" 
                                           name="expertise_date"
                                           value="{{ old('expertise_date', $contract->expertise_date ? \Carbon\Carbon::parse($contract->expertise_date)->format('Y-m-d') : '') }}">
                                    @error('expertise_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="deposit" class="form-label">Accompte</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}" 
                                               id="deposit" 
                                               name="deposit"
                                               value="{{ old('deposit', $contract->deposit) }}">
                                    </div>
                                    @error('deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="remaining_amount" class="form-label">Reste à payer</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control" 
                                               id="remaining_amount" 
                                               name="remaining_amount" 
                                               value="{{ old('remaining_amount', $contract->remaining_amount) }}" 
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="payment_condition" class="form-label">Conditions de paiement <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('payment_condition') ? 'is-invalid' : '' }}" 
                                            id="payment_condition" 
                                            name="payment_condition" 
                                            required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="cash" {{ old('payment_condition', $contract->payment_condition) == 'cash' ? 'selected' : '' }}>Comptant</option>
                                        <option value="leasing" {{ old('payment_condition', $contract->payment_condition) == 'leasing' ? 'selected' : '' }}>Leasing</option>
                                        <option value="credit" {{ old('payment_condition', $contract->payment_condition) == 'credit' ? 'selected' : '' }}>Crédit</option>
                                    </select>
                                    @error('payment_condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="warranty" class="form-label">Garantie <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('warranty') ? 'is-invalid' : '' }}" 
                                            id="warranty" 
                                            name="warranty" 
                                            required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="no_warranty" {{ old('warranty', $contract->warranty) == 'no_warranty' ? 'selected' : '' }}>Sans garantie (export)</option>
                                        <option value="quality_1_qbase" {{ old('warranty', $contract->warranty) == 'quality_1_qbase' ? 'selected' : '' }}>Garantie Quality 1 QBase</option>
                                        <option value="quality_1_q3" {{ old('warranty', $contract->warranty) == 'quality_1_q3' ? 'selected' : '' }}>Garantie Quality 1 Q3</option>
                                        <option value="quality_1_q5" {{ old('warranty', $contract->warranty) == 'quality_1_q5' ? 'selected' : '' }}>Garantie Quality 1 Q5</option>
                                    </select>
                                    @error('warranty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3" 
                                     id="warrantyAmountSection" 
                                     style="display: {{ old('warranty', $contract->warranty) == 'quality_1_q5' ? 'block' : 'none' }};">
                                    <label for="warranty_amount" class="form-label">Montant supplémentaire pour Q5</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control {{ $errors->has('warranty_amount') ? 'is-invalid' : '' }}" 
                                               id="warranty_amount" 
                                               name="warranty_amount"
                                               value="{{ old('warranty_amount', $contract->warranty_amount) }}">
                                    </div>
                                    @error('warranty_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Enregistrer le contrat
                            </button>
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
    // Get form elements
    const form = document.getElementById('contractForm');
    const chassisInput = document.getElementById('chassis_number');
    const searchBtn = document.getElementById('searchVehicle');

    // Only proceed if the form and required elements exist
    if (!form || !chassisInput) return;

    // Remove validation errors when user starts typing
    document.querySelectorAll('input, select').forEach(function(element) {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        });
    });

    // Add input validation for VIN
    chassisInput.addEventListener('input', function() {
        // Remove any non-alphanumeric characters and convert to uppercase
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        // Limit to 17 characters
        if (this.value.length > 17) {
            this.value = this.value.substring(0, 17);
        }
    });

    // Vehicle search functionality
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const chassisNumber = chassisInput.value.trim();
            
            if (!chassisNumber || chassisNumber.length !== 17) {
                showFieldError(chassisInput, 'Le numéro de châssis doit contenir exactement 17 caractères.');
                return;
            }

            // Show loading
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';

            // API call
            fetch('{{ route('contracts.vehicle.info') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ chassis_number: chassisNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.decode && Array.isArray(data.decode)) {
                    data.decode.forEach(item => {
                        if (item.label === 'Make') {
                            const brandInput = document.getElementById('vehicle_brand');
                            if (brandInput) brandInput.value = item.value || '';
                        } else if (item.label === 'Model') {
                            const typeInput = document.getElementById('vehicle_type');
                            if (typeInput) typeInput.value = item.value || '';
                        }
                    });
                } else {
                    throw new Error('Aucune information trouvée');
                }
            })
            .catch(error => {
                showFieldError(chassisInput, 'Impossible de récupérer les informations du véhicule.');
            })
            .finally(() => {
                searchBtn.disabled = false;
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Rechercher';
            });
        });
    }

    // Helper function to show field errors
    function showFieldError(input, message) {
        if (!input) return;
        
        input.classList.add('is-invalid');
        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
        feedback.style.display = 'block';
    }

    // Calculate remaining amount
    const salePriceInput = document.getElementById('sale_price');
    const depositInput = document.getElementById('deposit');
    const remainingAmountInput = document.getElementById('remaining_amount');
    
    function updateRemainingAmount() {
        if (!salePriceInput || !depositInput || !remainingAmountInput) return;
        
        const salePrice = parseFloat(salePriceInput.value) || 0;
        const deposit = parseFloat(depositInput.value) || 0;
        remainingAmountInput.value = Math.max(0, salePrice - deposit).toFixed(2);
    }

    if (salePriceInput) salePriceInput.addEventListener('input', updateRemainingAmount);
    if (depositInput) depositInput.addEventListener('input', updateRemainingAmount);

    // Warranty amount visibility
    const warrantySelect = document.getElementById('warranty');
    const warrantySection = document.getElementById('warrantyAmountSection');
    if (warrantySelect && warrantySection) {
        warrantySelect.addEventListener('change', function() {
            warrantySection.style.display = this.value === 'quality_1_q5' ? 'block' : 'none';
        });
    }

    // Clear birth date button
    const clearBirthDateBtn = document.getElementById('clearBirthDate');
    if (clearBirthDateBtn) {
        clearBirthDateBtn.addEventListener('click', function() {
            const birthDateInput = document.getElementById('buyer_birth_date');
            if (birthDateInput) {
                birthDateInput.value = '';
                birthDateInput.classList.remove('is-invalid');
                const feedback = birthDateInput.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.style.display = 'none';
                }
            }
        });
    }

    // Form submission
    form.addEventListener('submit', function(event) {
        let formIsValid = true;
        
        // Clear empty date fields to prevent NULL submission
        const birthDateInput = document.getElementById('buyer_birth_date');
        if (birthDateInput && !birthDateInput.value) {
            birthDateInput.disabled = true; // This prevents empty string from being sent
        }
        
        // Manually validate VIN
        const chassisNumber = chassisInput.value.trim();
        if (chassisNumber.length !== 17) {
            showFieldError(chassisInput, 'Le numéro de châssis doit contenir exactement 17 caractères.');
            formIsValid = false;
        } else {
            // Remove error state if VIN is valid
            chassisInput.classList.remove('is-invalid');
            const feedback = chassisInput.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
        
        // Prevent form submission if validation fails
        if (!formIsValid) {
            event.preventDefault();
            // Scroll to the first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
@endpush
@endsection