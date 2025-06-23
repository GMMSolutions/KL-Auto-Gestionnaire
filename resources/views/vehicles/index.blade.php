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
        
        let currentVehicleId = null;
        
        // Show repairs when clicking the button
        $(document).on('click', '.view-repairs', function(e) {
            e.preventDefault();
            
            currentVehicleId = $(this).data('vehicle-id');
            const vehicleName = $(this).data('vehicle-name');
            
            $('#vehicleName').text(vehicleName);
            $('#vehicleId').val(currentVehicleId);
            
            // Reset form and UI state
            $('#repairsList').html(`
                <div class="text-center text-muted py-3">
                    <i class="bi bi-hourglass-split fs-1"></i>
                    <p class="mt-2">Chargement des réparations...</p>
                </div>
            `);
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('repairsModal'));
            modal.show();
            
            // Load repairs after a small delay to ensure modal is shown
            setTimeout(() => {
                loadRepairs(currentVehicleId);
            }, 100);
        });
        
        // Reset modal when hidden
        $('#repairsModal').on('hidden.bs.modal', function () {
            $('#repairsList').html('');
            $('#addRepairForm').addClass('d-none');
            $('#repairForm')[0].reset();
            currentVehicleId = null;
        });
        
        // Load repairs function
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
                                            <th class="text-end">Montant</th>
                                            <th class="text-end">Date</th>
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
                                    <td class="text-end">${parseFloat(repair.amount).toFixed(2)} CHF</td>
                                    <td class="text-end">${formattedDate}</td>
                                    <td class="text-end">
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
                                        <td class="text-end">${total.toFixed(2)} CHF</td>
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
                        $('#repairsList').html(`
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
        
        // Show add repair form
        $(document).on('click', '#addRepairBtn', function(e) {
            e.preventDefault();
            $('#repairsList').addClass('d-none');
            $('#addRepairForm').removeClass('d-none');
            $(this).hide();
        });
        
        // Cancel add repair
        $(document).on('click', '#cancelRepairBtn', function(e) {
            e.preventDefault();
            $('#repairForm')[0].reset();
            $('#addRepairForm').addClass('d-none');
            $('#repairsList').removeClass('d-none');
            $('#addRepairBtn').show();
        });
        
        // Submit repair form
        $(document).on('submit', '#repairForm', function(e) {
            e.preventDefault();
            
            if (!currentVehicleId) return;
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: `/vehicles/${currentVehicleId}/repairs`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Réparation ajoutée avec succès');
                        $('#repairForm')[0].reset();
                        loadRepairs(currentVehicleId);
                        $('#addRepairForm').addClass('d-none');
                        $('#repairsList').removeClass('d-none');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || 'Une erreur est survenue';
                    showAlert('danger', error);
                }
            });
        });
        
        // Delete repair
        $(document).on('click', '.delete-repair', function() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette réparation ?') || !currentVehicleId) {
                return;
            }
            
            const repairId = $(this).data('id');
            
            $.ajax({
                url: `/vehicles/${currentVehicleId}/repairs/${repairId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Réparation supprimée avec succès');
                        loadRepairs(currentVehicleId);
                    }
                },
                error: function() {
                    showAlert('danger', 'Une erreur est survenue lors de la suppression');
                }
            });
        });
        
        // Helper function to show alerts
        function showAlert(type, message) {
            const alert = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            `);
            
            $('.modal-body').prepend(alert);
            
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
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
                            <label for="amount" class="form-label">Montant (CHF)</label>
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
