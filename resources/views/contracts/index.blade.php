@extends('layouts.app')

@section('title', 'Contrats')

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
    
    <!-- Simple Search Bar -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
        </div>
    </div>
    
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
                            <a href="{{ route('contracts.pdf', $contract) }}" class="btn btn-sm btn-outline-primary" title="Voir le PDF" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('contracts.download', $contract) }}" class="btn btn-sm btn-outline-success" title="Télécharger le PDF">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route($contract->contract_type === 'vente' ? 'contracts.editsale' : 'contracts.editpurchase', $contract) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-contract" 
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
    
    <!-- Simple pagination info -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <small class="text-muted">Total: {{ count($contracts) }} contrat(s)</small>
        </div>
    </div>
</div>

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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('contracts-table');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length - 1; j++) { // Exclude actions column
                if (cells[j].textContent.toLowerCase().includes(filter)) {
                    found = true;
                    break;
                }
            }
            
            rows[i].style.display = found ? '' : 'none';
        }
    });

    // Handle delete confirmation
    let contractIdToDelete = null;
    
    // When delete button is clicked, store the contract ID
    document.querySelectorAll('.delete-contract').forEach(button => {
        button.addEventListener('click', function() {
            contractIdToDelete = this.getAttribute('data-id');
        });
    });

    // When confirm delete is clicked
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!contractIdToDelete) return;
        
        const deleteUrl = `{{ url('contracts') }}/${contractIdToDelete}/delete`;
        
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Show success message
            showToast('Succès', data.message, 'success');
            
            // Remove the row from the table
            const button = document.querySelector(`button[data-id="${contractIdToDelete}"]`);
            if (button) {
                button.closest('tr').remove();
            }
            
            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteContractModal'));
            if (modal) {
                modal.hide();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erreur', 'Une erreur est survenue lors de la suppression du contrat.', 'danger');
            
            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteContractModal'));
            if (modal) {
                modal.hide();
            }
        });
    });

    // Simple toast function
    function showToast(title, message, type) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type} text-white">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fermer"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>
@endpush