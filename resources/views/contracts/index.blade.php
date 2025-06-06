@extends('layouts.app')

@section('title', 'Contrats')

@push('styles')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
        }
        .dt-button {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .dataTables_wrapper .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
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
    
    <div class="table-responsive">
        <table id="contracts-table" class="table">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Nom & Prénom</th>
                    <th>Véhicule</th>
                    <th>N° de châssis</th>
                    <th class="text-end">Prix de vente TVA</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                <tr>
                    <td>
                        <span class="badge {{ $contract->contract_type === 'vente' ? 'bg-success' : 'bg-info' }}">
                            {{ ucfirst($contract->contract_type) }}
                        </span>
                    </td>
                    <td>{{ $contract->buyer_surname }} {{ $contract->buyer_name }}</td>
                    <td>{{ $contract->vehicle_brand }} {{ $contract->vehicle_type }}</td>
                    <td>{{ $contract->chassis_number }}</td>
                    <td class="text-end">{{ number_format($contract->sale_price, 2, ',', ' ') }} €</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('contracts.pdf', $contract) }}" class="btn btn-sm btn-outline-primary btn-action" title="Voir le PDF" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('contracts.download', $contract) }}" class="btn btn-sm btn-outline-success btn-action" title="Télécharger le PDF">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route($contract->contract_type === 'vente' ? 'contracts.editsale' : 'contracts.editpurchase', $contract) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-contract" 
                                    title="Supprimer" 
                                    data-id="{{ $contract->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteContractModal">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>

<!-- Buttons HTML5 Export -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

<!-- PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with enhanced settings
        try {
            var table = $('#contracts-table').DataTable({
                // Custom DOM layout with buttons and search
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12 mb-3'B>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                
                // Enable features
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                
                // Buttons configuration
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Exporter',
                        buttons: [
                            'copy',
                            'excel',
                            'csv',
                            'pdf',
                            'print'
                        ]
                    },
                    'colvis'
                ],
                
                // Language settings
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
                    emptyTable: 'Aucune donnée disponible dans le tableau',
                    info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
                    infoEmpty: 'Affichage de 0 à 0 sur 0 entrées',
                    infoFiltered: '(filtrées depuis un total de _MAX_ entrées)',
                    lengthMenu: 'Afficher _MENU_ entrées',
                    loadingRecords: 'Chargement...',
                    processing: 'Traitement...',
                    search: 'Rechercher :',
                    zeroRecords: 'Aucun enregistrement correspondant trouvé',
                    buttons: {
                        copy: 'Copier',
                        copyTitle: 'Copier dans le presse-papier',
                        copySuccess: {
                            _: '%d lignes copiées',
                            1: '1 ligne copiée'
                        },
                        print: 'Imprimer',
                        pageLength: 'Afficher %d lignes',
                        collection: 'Exporter <span class=\'ui-button-icon-primary ui-icon ui-icon-triangle-1-s\'/>',
                        colvis: 'Visibilité des colonnes',
                        colvisRestore: 'Réinitialiser la visibilité'
                    }
                },
                
                // Default settings
                order: [],
                pageLength: 10,
                
                // Column definitions
                columnDefs: [
                    { orderable: false, targets: [5] }, // Disable sorting on actions column
                    { className: 'text-end', targets: [4] }, // Right-align price column
                    { responsivePriority: 1, targets: 0 }, // Type column
                    { responsivePriority: 2, targets: 5 }, // Actions column
                    { width: '10%', targets: 0 }, // Type column width
                    { width: '15%', targets: 5 }  // Actions column width
                ],
                
                // Styling
                initComplete: function() {
                    // Add custom classes to buttons
                    $('.dt-button').addClass('btn btn-sm btn-light');
                    $('.buttons-collection').removeClass('btn-light').addClass('btn-primary');
                },
                
                // Error handling
                error: function(xhr, error, thrown) {
                    console.error('DataTables error:', error);
                }
            });
        } catch (e) {
            console.error('Error initializing DataTable:', e);
        }

        // Handle delete confirmation
        let contractIdToDelete = null;
        const deleteModal = document.getElementById('deleteContractModal');
        
        // When delete button is clicked, store the contract ID
        $('#contracts-table tbody').on('click', '.delete-contract', function() {
            contractIdToDelete = $(this).data('id');
        });

        // When confirm delete is clicked
        $('#confirmDelete').on('click', function() {
            if (!contractIdToDelete) return;
            
            const deleteUrl = `{{ url('contracts') }}/${contractIdToDelete}/delete`;
            
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
                    
                    // Remove the row from the table
                    $(`button[data-id="${contractIdToDelete}"]`).closest('tr').fadeOut(400, function() {
                        $(this).remove();
                    });
                    
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
