@extends('layouts.app')

@section('title', 'Contrats')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Responsive buttons */
        @media (max-width: 768px) {
            /* Make action buttons smaller */
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
            
            /* Adjust spacing between buttons */
            .btn-group .btn:not(:last-child) {
                margin-right: 0.15rem;
            }
            
            /* Make new sale/purchase buttons smaller */
            .d-flex.gap-2 .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
            
            /* Adjust button icons */
            .btn i {
                margin-right: 0.25rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Contrats</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('contracts.createsale') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Nouvelle Vente
                    </a>
                    <a href="{{ route('contracts.createpurchase') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nouvel Achat
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    
    <div class="table-responsive p-2">
        <table id="contracts-table" class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Nom complet</th>
                    <th>Véhicule</th>
                    <th>N° de châssis (VIN)</th>
                    <th class="text-end">Prix de vente TVA incluse</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded by DataTables -->
            </tbody>
        </table>
    </div>
</div>
@endsection

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteContractModal" tabindex="-1" aria-labelledby="deleteContractModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteContractModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce contrat ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with server-side processing
        var table = $('#contracts-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("contracts.index") }}',
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            columns: [
                { data: 'contract_type', name: 'contract_type' },
                { 
                    data: null,
                    name: 'buyer_name',
                    render: function(data) {
                        return data.buyer_surname + ' ' + data.buyer_name;
                    }
                },
                { 
                    data: null,
                    name: 'vehicle_brand',
                    render: function(data) {
                        return data.vehicle_brand + ' ' + data.vehicle_type;
                    }
                },
                { data: 'chassis_number', name: 'chassis_number' },
                { 
                    data: 'sale_price', 
                    name: 'sale_price',
                    className: 'text-end',
                    render: function(data) {
                        return 'CHF ' + parseFloat(data).toLocaleString('fr-CH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).replace(/,/g, ' ');
                    }
                },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <a href="/contracts/${data}/pdf" class="btn btn-outline-primary px-3" title="Voir le PDF" target="_blank">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/contracts/${data}/download" class="btn btn-outline-success px-3" title="Télécharger le PDF">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="/contracts/${row.contract_type === 'vente' ? 'editsale' : 'editpurchase'}/${data}" class="btn btn-outline-secondary px-3" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger px-3 delete-contract" 
                                        title="Supprimer" 
                                        data-id="${data}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteContractModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>`;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
                emptyTable: 'Aucune donnée disponible dans le tableau',
                zeroRecords: 'Aucun enregistrement correspondant trouvé'
            },
            pageLength: 25,
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 }, // Type column
                { responsivePriority: 2, targets: 5 }  // Actions column
            ]
        });

        // Handle delete confirmation
        let contractIdToDelete = null;
        const deleteModal = document.getElementById('deleteContractModal');
        
        // When delete button is clicked, store the contract ID
        $('#contracts-table').on('click', '.delete-contract', function(e) {
            e.preventDefault();
            contractIdToDelete = $(this).data('id');
        });

        // When confirm delete is clicked
        $('#confirmDelete').on('click', function() {
            if (!contractIdToDelete) return;
            
            const deleteUrl = `{{ url('contracts') }}/${contractIdToDelete}/delete`;
            const $button = $(`button[data-id="${contractIdToDelete}"]`);
            
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed top-0 end-0 p-3';
                    toast.innerHTML = `
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header bg-success text-white">
                                <strong class="me-auto">Succès</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fermer"></button>
                            </div>
                            <div class="toast-body">
                                ${response.message}
                            </div>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    // Reload the table data
                    table.ajax.reload();
                    
                    // Hide the modal
                    const modal = bootstrap.Modal.getInstance(deleteModal);
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Remove toast after 3 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                },
                error: function(xhr) {
                    // Show error message
                    const response = xhr.responseJSON || {};
                    const message = response.message || 'Une erreur est survenue lors de la suppression du contrat.';
                    
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed top-0 end-0 p-3';
                    toast.innerHTML = `
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header bg-danger text-white">
                                <strong class="me-auto">Erreur</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fermer"></button>
                            </div>
                            <div class="toast-body">
                                ${message}
                            </div>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    // Hide the modal and remove backdrop
                    const modal = bootstrap.Modal.getInstance(deleteModal);
                    if (modal) {
                        modal.hide();
                        // Remove backdrop manually if needed
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                    
                    // Remove toast after 5 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 5000);
                }
            });
        });
    });
</script>
@endpush