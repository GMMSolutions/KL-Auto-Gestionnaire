@extends('layouts.app')

@section('title', 'Liste des Contrats')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <h1 class="mb-0">Liste des Contrats</h1>
                <a href="{{ route('contracts.createsale') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Nouvelle Vente
                </a>
                <a href="{{ route('contracts.createpurchase') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nouvel Achat
                </a>
            </div>
        </div>
    </div>
    <hr>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="contracts-table" class="table table-striped table-hover">
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
<script>
    $(document).ready(function() {
        // Initialize DataTable with standard settings
        var table = $('#contracts-table').DataTable({
            // Standard DOM structure with search and pagination
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            // Basic features
            paging: true,
            searching: true,
            ordering: true,
            // Disable features that might interfere
            colReorder: false,
            stateSave: false,
            // Responsive settings
            responsive: true,
            // Language settings
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
                emptyTable: 'Aucune donnée disponible dans le tableau',
                zeroRecords: 'Aucun enregistrement correspondant trouvé'
            },
            order: [],
            pageLength: 25,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [5] }, // Disable sorting on actions column
                { className: 'text-end', targets: [4] }, // Right-align price column
                { responsivePriority: 1, targets: 0 }, // Type column
                { responsivePriority: 2, targets: 5 }  // Actions column
            ]
        });

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

<style>
.toast {
    z-index: 1100;
    min-width: 300px;
}
</style>
@endpush
