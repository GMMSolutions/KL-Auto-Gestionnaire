<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Contrat de Vente - {{ $contract->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.4; margin: 40px; }
        h1 { text-align: center; font-size: 20px; margin-bottom: 10px; }
        .sub-header { text-align: center; margin-bottom: 30px; }
        .sub-header p { margin: 2px 0; }

        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; background: #eee; padding: 6px; border: 1px solid #ccc; margin-bottom: 8px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background: #f5f5f5; text-align: left; }

        .two-columns { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .column { width: 48%; border: 1px solid #000; padding: 10px; }

        .signature-block { margin-top: 30px; display: flex; justify-content: space-between; }
        .signature { width: 45%; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 80%; margin: 50px auto 10px; }

        .warranty-box { border: 1px solid #000; padding: 10px; background: #f9f9f9; margin-top: 8px; }

        .footer-note { text-align: center; font-size: 11px; margin-top: 30px; color: #555; }
    </style>
</head>
<body>

    <h1>CONTRAT DE VENTE DE VÉHICULE D'OCCASION</h1>

    <div class="sub-header">
        <p>N° de contrat : <strong>{{ $contract->id }}</strong></p>
        <p>Fait à <strong>{{ config('app.city') }}</strong>, le <strong>{{ \Carbon\Carbon::parse($contract->created_at)->format('d/m/Y') }}</strong></p>
    </div>

    <div class="two-columns">
        <div class="column">
            <strong>LE VENDEUR :</strong>
            <p>{{ config('app.name') }}</p>
            <p>{{ config('app.address') }}</p>
            <p>Représenté par : {{ config('app.representative') }}</p>
        </div>
        <div class="column">
            <strong>L'ACHETEUR :</strong>
            <p>{{ $contract->buyer_surname }} {{ $contract->buyer_name }}</p>
            <p>{{ $contract->buyer_address }}, {{ $contract->buyer_zip }} {{ $contract->buyer_city }}</p>
            <p>Né(e) le : {{ $contract->buyer_birth_date ? \Carbon\Carbon::parse($contract->buyer_birth_date)->format('d/m/Y') : 'Non renseigné' }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">DÉSIGNATION DU VÉHICULE</div>
        <table>
            <tr>
                <th>Marque</th>
                <td>{{ $contract->vehicle_brand }}</td>
                <th>Type</th>
                <td>{{ $contract->vehicle_type }}</td>
            </tr>
            <tr>
                <th>Immatriculation</th>
                <td>{{ $contract->plate_number ?? 'Non immatriculé' }}</td>
                <th>N° de châssis</th>
                <td>{{ $contract->chassis_number }}</td>
            </tr>
            <tr>
                <th>1ère Immatriculation</th>
                <td>{{ \Carbon\Carbon::parse($contract->first_registration_date)->format('d/m/Y') }}</td>
                <th>Kilométrage</th>
                <td>{{ number_format($contract->mileage, 0, ',', ' ') }} km</td>
            </tr>
            <tr>
                <th>Couleur</th>
                <td>{{ $contract->color }}</td>
                <th>Accidenté</th>
                <td>{{ $contract->has_accident ? 'Oui' : 'Non' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">PRIX ET MODALITÉS DE PAIEMENT</div>
        <table>
            <tr>
                <th>Prix de vente TTC</th>
                <td colspan="3"><strong>{{ number_format($contract->sale_price, 2, ',', ' ') }} €</strong></td>
            </tr>
            @if($contract->deposit > 0)
            <tr>
                <th>Accompte</th>
                <td>{{ number_format($contract->deposit, 2, ',', ' ') }} €</td>
                <th>Reste à payer</th>
                <td>{{ number_format(($contract->sale_price - $contract->deposit), 2, ',', ' ') }} €</td>
            </tr>
            @endif
            <tr>
                <th>Mode de paiement</th>
                <td colspan="3">
                    @switch($contract->payment_condition)
                        @case('cash') Comptant @break
                        @case('leasing') Crédit-bail @break
                        @case('credit') Crédit @break
                        @default Non spécifié
                    @endswitch
                </td>
            </tr>
            @if($contract->expertise_date)
            <tr>
                <th>Date d'expertise</th>
                <td colspan="3">{{ \Carbon\Carbon::parse($contract->expertise_date)->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">GARANTIE</div>
        @if($contract->warranty === 'no_warranty')
            <p>Le véhicule est vendu en l'état, sans garantie légale.</p>
        @else
            <div class="warranty-box">
                <p><strong>Type :</strong>
                    @switch($contract->warranty)
                        @case('quality_1_qbase') 1 mois base @break
                        @case('quality_1_q3') 3 mois @break
                        @case('quality_1_q5') 5 mois @break
                    @endswitch
                </p>
                @if($contract->warranty === 'quality_1_q5' && $contract->warranty_amount)
                    <p><strong>Montant :</strong> {{ number_format($contract->warranty_amount, 2, ',', ' ') }} €</p>
                @endif
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">CONDITIONS PARTICULIÈRES</div>
        <p>L'acheteur déclare avoir examiné le véhicule et l'avoir trouvé conforme.</p>
        <p>Litige : tribunaux de {{ config('app.city') }} seuls compétents.</p>
    </div>

    <div class="signature-block">
        <div class="signature">
            <p>Fait à {{ config('app.city') }}, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            <p>Le Vendeur</p>
            <div class="signature-line"></div>
            <p>{{ config('app.representative') }}</p>
        </div>
        <div class="signature">
            <p>Fait à ......................, le ../../....</p>
            <p>L'Acheteur</p>
            <div class="signature-line"></div>
            <p>{{ $contract->buyer_surname }} {{ $contract->buyer_name }}</p>
        </div>
    </div>

    <div class="footer-note">
        Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
