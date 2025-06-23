@extends('layouts.app')

@section('title', 'KL Automobiles - Véhicules')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table td, .table th {
            white-space: nowrap;
        }
        .dataTables_wrapper .dataTables_scroll {
            overflow-x: auto;
            margin-bottom: 0;
        }
        .dataTables_scrollBody {
            overflow-x: auto !important;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Véhicules</h1>
            </div>
        </div>
    </div>
    <hr>
    
    <table id="vehicles-table" class="table w-100 pb-2">
        <thead>
            <tr>
                <th>Marque</th>
                <th>Type</th>
                <th>N° de châssis (VIN)</th>
                <th>Date d'ajout</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->vehicle_brand }}</td>
                <td>{{ $vehicle->vehicle_type }}</td>
                <td>{{ $vehicle->chassis_number }}</td>
                <td>{{ $vehicle->created_at->format('d.m.Y H:i') }}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary view-repairs" 
                            data-vehicle-id="{{ $vehicle->id }}"
                            data-vehicle-name="{{ $vehicle->vehicle_brand }} {{ $vehicle->vehicle_type }}"
                            data-bs-toggle="modal" 
                            data-bs-target="#repairsModal">
                        <i class="bi bi-tools"></i> Réparations
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
    // Initialize DataTable
    $('#vehicles-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
            emptyTable: 'Aucun véhicule trouvé',
            zeroRecords: 'Aucun enregistrement correspondant trouvé',
            info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
            infoEmpty: 'Aucune entrée à afficher',
            infoFiltered: '(filtré à partir de _MAX_ entrées totales)',
            search: 'Rechercher :',
            lengthMenu: 'Afficher _MENU_ entrées par page',
            paginate: {
                first: 'Premier',
                last: 'Dernier',
                next: 'Suivant',
                previous: 'Précédent'
            }
        },
        order: [[3, 'desc']], // Sort by creation date by default
        pageLength: 10,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        columnDefs: [
            { orderable: false, targets: -1 } // Disable sorting on actions column
        ]
    });
    
    // Show repairs when clicking the button
    $(document).on('click', '.view-repairs', function() {
        const vehicleId = $(this).data('vehicle-id');
        const vehicleName = $(this).data('vehicle-name');
        
        // Store the current vehicle ID in the modal's data
        const $modal = $('#repairsModal');
        $modal.data('vehicle-id', vehicleId);
        
        $('#vehicleName').text(vehicleName);
        $('#vehicleId').val(vehicleId);
        
        // Reset modal state completely
        resetModalState($modal);
        
        // Load repairs
        loadRepairs(vehicleId);
    });
    
    // Reset modal when hidden - FIXED
    $('#repairsModal').on('hidden.bs.modal', function () {
        const $modal = $(this);
        resetModalState($modal);
    });
    
    // NEW: Function to properly reset modal state
    function resetModalState($modal) {
        // Clear any existing alerts
        $modal.find('.alert').remove();
        
        // Reset form
        $modal.find('#repairForm')[0].reset();
        
        // Hide form and show repairs list
        $modal.find('#addRepairForm').addClass('d-none');
        $modal.find('#repairsList').removeClass('d-none').html(`
            <div class="text-center text-muted py-3">
                <i class="bi bi-hourglass-split fs-1"></i>
                <p class="mt-2">Chargement des réparations...</p>
            </div>
        `);
        
        // Make sure add repair button is visible (will be updated by loadRepairs)
        $modal.find('#addRepairBtn').show();
    }
    
    // Load repairs function - IMPROVED
    function loadRepairs(vehicleId) {
        const $modal = $('#repairsModal');
        
        $.get(`/vehicles/${vehicleId}/repairs`)
            .done(function(response) {
                if (response.data.length > 0) {
                    let html = `
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    let total = 0;
                    
                    response.data.forEach(function(repair) {
                        const date = new Date(repair.created_at);
                        const formattedDate = date.toLocaleDateString('fr-CH') + ' ' + date.toLocaleTimeString('fr-CH', {hour: '2-digit', minute:'2-digit'});
                        
                        html += `
                            <tr>
                                <td>${repair.description}</td>
                                <td>CHF ${parseFloat(repair.amount).toFixed(2)}</td>
                                <td>${formattedDate}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger delete-repair" data-id="${repair.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        
                        total += parseFloat(repair.amount);
                    });
                    
                    html += `
                                <tr class="table-secondary fw-bold">
                                    <td>Total</td>
                                    <td>CHF ${total.toFixed(2)}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm" id="addRepairBtn">
                            <i class="bi bi-plus"></i> Ajouter une réparation
                        </button>
                    </div>`;
                    
                    $modal.find('#repairsList').html(html);
                } else {
                    $modal.find('#repairsList').html(`
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-tools fs-1"></i>
                            <p class="mt-2">Aucune réparation enregistrée pour ce véhicule</p>
                            <button class="btn btn-primary btn-sm mt-2" id="addRepairBtn">
                                <i class="bi bi-plus"></i> Ajouter une réparation
                            </button>
                        </div>
                    `);
                }
            })
            .fail(function() {
                $modal.find('#repairsList').html(`
                    <div class="alert alert-danger">
                        Une erreur est survenue lors du chargement des réparations
                    </div>
                `);
            });
    }
    
    // Show add repair form - IMPROVED
    $(document).on('click', '#addRepairBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $modal = $('#repairsModal');
        
        // Hide repairs list and show form
        $modal.find('#repairsList').addClass('d-none');
        $modal.find('#addRepairForm').removeClass('d-none');
        
        // Focus on first input
        $modal.find('#description').focus();
    });
    
    // Cancel add repair - IMPROVED
    $(document).on('click', '#cancelRepairBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $modal = $('#repairsModal');
        
        // Reset form
        $modal.find('#repairForm')[0].reset();
        
        // Hide form and show repairs list
        $modal.find('#addRepairForm').addClass('d-none');
        $modal.find('#repairsList').removeClass('d-none');
    });
    
    // Submit repair form - IMPROVED
    $(document).on('submit', '#repairForm', function(e) {
        e.preventDefault();
        
        const vehicleId = $('#vehicleId').val();
        const formData = $(this).serialize();
        
        // Disable submit button to prevent double submission
        const $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true);
        
        $.ajax({
            url: `/vehicles/${vehicleId}/repairs`,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    const $modal = $('#repairsModal');
                    showAlert('success', 'Réparation ajoutée avec succès');
                    
                    // Reset form
                    $modal.find('#repairForm')[0].reset();
                    
                    // Hide form and show updated repairs list
                    $modal.find('#addRepairForm').addClass('d-none');
                    $modal.find('#repairsList').removeClass('d-none');
                    
                    // Reload repairs
                    loadRepairs(vehicleId);
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Une erreur est survenue';
                showAlert('danger', error);
            },
            complete: function() {
                // Re-enable submit button
                $submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Delete repair
    $(document).on('click', '.delete-repair', function() {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette réparation ?')) {
            return;
        }
        
        const repairId = $(this).data('id');
        const vehicleId = $('#vehicleId').val();
        
        $.ajax({
            url: `/vehicles/${vehicleId}/repairs/${repairId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Réparation supprimée avec succès');
                    loadRepairs(vehicleId);
                }
            },
            error: function() {
                showAlert('danger', 'Une erreur est survenue lors de la suppression');
            }
        });
    });
    
    // Handle PDF export button click
    $(document).on('click', '#exportPdfBtn', function(e) {
        e.preventDefault();
        const $modal = $('#repairsModal');
        const vehicleId = $modal.data('vehicle-id');
        if (vehicleId) {
            window.open(`/vehicles/${vehicleId}/repairs-pdf`, '_blank');
        } else {
            console.error('Vehicle ID not found');
            showAlert('danger', 'Impossible de générer le PDF : véhicule non trouvé');
        }
    });
    
    // Helper function to show alerts - IMPROVED
    function showAlert(type, message) {
        const $modal = $('#repairsModal');
        
        // Remove existing alerts first
        $modal.find('.alert').remove();
        
        const alert = $(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        `);
        
        $modal.find('.modal-body').prepend(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }
});
</script>

<!-- Repairs Modal -->
<div class="modal fade" id="repairsModal" tabindex="-1" aria-labelledby="repairsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="repairsModalLabel">Réparations pour <span id="vehicleName"></span></h5>
                <div>
                    <a href="#" class="btn btn-sm btn-outline-danger me-2" id="exportPdfBtn">
                        <i class="bi bi-printer"></i> Imprimer
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
            </div>
            <div class="modal-body">
                <div id="repairsList">
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-hourglass-split fs-1"></i>
                        <p class="mt-2">Chargement des réparations...</p>
                    </div>
                </div>
                
                <!-- Add Repair Form (Hidden by default) -->
                <div id="addRepairForm" class="d-none">
                    <h6>Nouvelle réparation</h6>
                    <form id="repairForm">
                        @csrf
                        <input type="hidden" name="vehicle_id" id="vehicleId">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Montant</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" required>
                                <span class="input-group-text">CHF</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-sm me-2" id="cancelRepairBtn">Annuler</button>
                            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endpush
