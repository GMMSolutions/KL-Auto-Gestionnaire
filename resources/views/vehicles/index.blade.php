@extends('layouts.app')

@section('title', 'KL Automobiles - Véhicules')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
    
    <div class="card">
        <div class="card-body">
            <table id="vehicles-table" class="table w-100">
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
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#vehicles-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
                emptyTable: 'Aucun véhicule trouvé',
                zeroRecords: 'Aucun enregistrement correspondant trouvé'
            },
            order: [[3, 'desc']], // Sort by creation date by default
            pageLength: 10,
            responsive: true
        });
    });
</script>
@endpush
