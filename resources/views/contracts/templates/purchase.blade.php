<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Contrat d'Achat - {{ $contract->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; border-bottom: 1px solid #000; margin-bottom: 10px; }
        .two-columns { display: flex; justify-content: space-between; }
        .column { width: 48%; }
        .signature { margin-top: 50px; }
        .signature-line { border-top: 1px solid #000; width: 200px; margin: 40px 0 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONTRAT D'ACHAT DE VÉHICULE D'OCCASION</h1>
        <p>N° de contrat: {{ $contract->id }}</p>
        <p>Fait à <strong>{{ config('app.city') }}</strong>, le <strong>{{ \Carbon\Carbon::parse($contract->created_at)->format('d/m/Y') }}</strong></p>
    </div>

    <div class="section">
        <div class="section-title">ENTRE LES SOUSSIGNÉS :</div>
        <p>La société <strong>{{ config('app.name') }}</strong>, dont le siège social est situé à {{ config('app.address') }}, représentée par {{ config('app.representative') }},</p>
        <p>ci-après dénommée « LE VENDEUR »,</p>
        <p>ET</p>
        <p>Monsieur/Madame <strong>{{ $contract->buyer_surname }} {{ $contract->buyer_name }}</strong>,</p>
        <p>demeurant à : {{ $contract->buyer_address }}, {{ $contract->buyer_zip }} {{ $contract->buyer_city }},</p>
        <p>Né(e) le : {{ $contract->buyer_birth_date ? \Carbon\Carbon::parse($contract->buyer_birth_date)->format('d/m/Y') : 'Non renseigné' }},</p>
        <p>ci-après dénommé(e) « L'ACHETEUR »,</p>
    </div>

    <div class="section">
        <div class="section-title">DÉSIGNATION DU VÉHICULE :</div>
        <div class="two-columns">
            <div class="column">
                <p><strong>Marque :</strong> {{ $contract->vehicle_brand }}</p>
                <p><strong>Type :</strong> {{ $contract->vehicle_type }}</p>
                <p><strong>Immatriculation :</strong> {{ $contract->plate_number ?? 'Non immatriculé' }}</p>
                <p><strong>N° de châssis :</strong> {{ $contract->chassis_number }}</p>
            </div>
            <div class="column">
                <p><strong>1ère immatriculation :</strong> {{ \Carbon\Carbon::parse($contract->first_registration_date)->format('d/m/Y') }}</p>
                <p><strong>Kilométrage :</strong> {{ number_format($contract->mileage, 0, ',', ' ') }} km</p>
                <p><strong>Couleur :</strong> {{ $contract->color }}</p>
                <p><strong>Véhicule accidenté :</strong> {{ $contract->has_accident ? 'Oui' : 'Non' }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">PRIX ET MODALITÉS DE PAIEMENT :</div>
        <p>Prix d'achat TTC : <strong>{{ number_format($contract->sale_price, 2, ',', ' ') }} €</strong></p>
        @if($contract->expertise_date)
        <p>Date d'expertise : {{ \Carbon\Carbon::parse($contract->expertise_date)->format('d/m/Y') }}</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">CONDITIONS PARTICULIÈRES :</div>
        <p>Le véhicule est vendu en l'état, sans garantie légale de conformité ni garantie des vices cachés.</p>
        <p>L'acheteur déclare avoir examiné le véhicule et l'avoir trouvé conforme à sa destination.</p>
    </div>

    <div class="two-columns">
        <div class="column">
            <div class="signature">
                <p>Fait à {{ config('app.city') }}, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <p>Pour le vendeur,</p>
                <div class="signature-line"></div>
                <p>{{ config('app.representative') }}</p>
                <p>{{ config('app.name') }}</p>
            </div>
        </div>
        <div class="column">
            <div class="signature">
                <p>Fait à ........................, le ../../....</p>
                <p>L'acheteur,</p>
                <div class="signature-line"></div>
                <p>{{ $contract->buyer_surname }} {{ $contract->buyer_name }}</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <p>Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
