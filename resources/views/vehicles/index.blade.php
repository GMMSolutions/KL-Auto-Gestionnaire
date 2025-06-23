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
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->vehicle_brand }}</td>
                <td>{{ $vehicle->vehicle_type }}</td>
                <td>{{ $vehicle->chassis_number }}</td>
                <td>{{ $vehicle->created_at->format('d.m.Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
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
            pageLength: 25,
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [
                { orderable: false, targets: -1 } // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush
