@extends('layouts.app')

@section('title', 'Contrats')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                <tr>
                    <td>
                        <span class="badge {{ $contract->contract_type === 'vente' ? 'bg-success' : 'bg-primary' }}">
                            {{ ucfirst($contract->contract_type) }}
                        </span>
                    </td>
                    <td>{{ $contract->buyer_surname }} {{ $contract->buyer_name }}</td>
                    <td>{{ $contract->vehicle_brand }} {{ $contract->vehicle_type }}</td>
                    <td>{{ $contract->chassis_number }}</td>
                    <td class="text-end">CHF {{ number_format($contract->sale_price, 2, ',', ' ') }}</td>
                    <td class="text-end">
                        <div class="btn-group" role="group" aria-label="Actions">
                            <a href="{{ route('contracts.pdf', $contract) }}" class="btn btn-outline-primary px-3" title="Voir le PDF" target="_blank">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('contracts.download', $contract) }}" class="btn btn-outline-success px-3" title="Télécharger le PDF">
                                <i class="bi bi-download"></i>
                            </a>
                            <a href="{{ route($contract->contract_type === 'vente' ? 'contracts.editsale' : 'contracts.editpurchase', $contract) }}" class="btn btn-outline-secondary px-3" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger px-3 delete-contract" 
                                    title="Supprimer" 
                                    data-id="{{ $contract->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteContractModal">
                                <i class="bi bi-trash"></i>
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
        // Initialize DataTable with standard settings
        var table = $('#contracts-table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
                emptyTable: 'Aucune donnée disponible dans le tableau',
                zeroRecords: 'Aucun enregistrement correspondant trouvé',
                paginate: {
                    first: '<<',
                    last: '>>',
                    next: '>',
                    previous: '<'
                }
            },
            order: [],
            pageLength: 25
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
                    // Refresh the page to show updated contract list
                    window.location.reload();
                },
                error: function(xhr) {
                    // Just close the modal on error
                    const modal = bootstrap.Modal.getInstance(deleteModal);
                    if (modal) {
                        modal.hide();
                        // Remove backdrop manually if needed
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                }
            });
        });
    });
</script>
@endpush