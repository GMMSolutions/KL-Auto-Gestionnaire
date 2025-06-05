@extends('layouts.app')

@section('title', 'Liste des Contrats')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            margin-left: 0.25rem;
            border-radius: 0.25rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0d6efd;
            color: white !important;
            border: 1px solid #0d6efd;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Liste des Contrats</h1>
                <div class="btn-group">
                    <a href="{{ route('contracts.createpurchase') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nouvel Achat
                    </a>
                    <a href="{{ route('contracts.createsale') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Nouvelle Vente
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" title="Imprimer">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-action" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Supprimer">
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#contracts-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            },
            order: [],
            pageLength: 25,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush
