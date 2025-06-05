<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Contrat de Vente - {{ $contract->id }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            line-height: 1.2; 
            margin: 20px;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #c41e3a;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        
        .company-details {
            font-size: 10px;
            margin-bottom: 5px;
        }
        
        .contract-title {
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0 10px 0;
        }
        
        .section {
            margin-bottom: 15px;
        }
        
        .section-header {
            background-color: #e6e6e6;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            border: 1px solid #000;
            margin-bottom: 0;
        }
        
        .buyer-info {
            border: 1px solid #000;
            border-top: none;
            padding: 8px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 3px;
        }
        
        .info-label {
            width: 35%;
            font-weight: normal;
        }
        
        .info-value {
            flex: 1;
            font-weight: bold;
        }
        
        .vehicle-section {
            border: 1px solid #000;
            margin-top: 15px;
        }
        
        .vehicle-header {
            background-color: #e6e6e6;
            padding: 4px 8px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #000;
        }
        
        .vehicle-info {
            padding: 8px;
        }
        
        .vehicle-row {
            display: flex;
            margin-bottom: 3px;
        }
        
        .vehicle-label {
            width: 40%;
        }
        
        .vehicle-value {
            flex: 1;
            font-weight: bold;
        }
        
        .checkbox-section {
            margin: 8px 0;
        }
        
        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        .checkbox.checked::after {
            content: "✓";
            font-size: 10px;
            font-weight: bold;
            display: block;
            text-align: center;
            line-height: 10px;
        }
        
        .price-section {
            margin: 15px 0;
        }
        
        .price-row {
            display: flex;
            margin-bottom: 3px;
        }
        
        .price-label {
            width: 40%;
        }
        
        .price-value {
            flex: 1;
            font-weight: bold;
        }
        
        .warranty-section {
            border: 2px solid #c41e3a;
            margin: 15px 0;
        }
        
        .warranty-header {
            background-color: #f0f0f0;
            padding: 4px 8px;
            font-weight: bold;
            color: #c41e3a;
            border-bottom: 1px solid #c41e3a;
        }
        
        .warranty-content {
            padding: 8px;
        }
        
        .warranty-option {
            margin: 3px 0;
            display: flex;
            align-items: center;
        }
        
        .declaration {
            font-size: 9px;
            margin: 15px 0;
            line-height: 1.3;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-left, .signature-right {
            width: 45%;
        }
        
        .signature-date {
            margin-bottom: 20px;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 40px;
            margin: 10px 0;
        }
        
        .underline {
            text-decoration: underline;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ config('app.name', 'KL AUTOMOBILES SA') }}</div>
        <div class="company-details">
            {{ config('app.address', 'Rte de Bussigny 22 - 1023 Crissier') }} - {{ config('app.phone', '+4179 500 67 67') }}<br>
            TVA {{ config('app.vat', '.109.519.355') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            IBAN {{ config('app.iban', 'CH90 0900 0000 1770 9550 0') }}
        </div>
        <div class="contract-title">CONTRAT DE VENTE D'UN VEHICULE D'OCCASION</div>
    </div>

    <div class="section">
        <div class="section-header">Acheteur</div>
        <div class="buyer-info">
            <div class="info-row">
                <div class="info-label">Nom, Prénom</div>
                <div class="info-value">{{ $contract->buyer_surname ?? 'Treur, Elodie' }}, {{ $contract->buyer_name ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de naissance</div>
                <div class="info-value">{{ $contract->buyer_birth_date ? \Carbon\Carbon::parse($contract->buyer_birth_date)->format('d.m.Y') : '' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Adresse (Rue, Numéro)</div>
                <div class="info-value">{{ $contract->buyer_address ?? 'Chemin de Bellevue 8' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Code Postal / Ville</div>
                <div class="info-value">{{ $contract->buyer_zip ?? '1033' }} {{ $contract->buyer_city ?? 'Cheseaux-Sur-Lausanne' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">N° de Téléphone</div>
                <div class="info-value">{{ $contract->buyer_phone ?? '.077.470.39.14.' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $contract->buyer_email ?? '' }}</div>
            </div>
        </div>
    </div>

    <div class="vehicle-section">
        <div class="vehicle-header">OBJET DU CONTRAT</div>
        <div class="vehicle-info">
            <div class="vehicle-row">
                <div class="vehicle-label">Marque et Type :</div>
                <div class="vehicle-value">{{ $contract->vehicle_brand ?? 'Kia' }} {{ $contract->vehicle_type ?? 'Soul' }}</div>
            </div>
            <div class="vehicle-row">
                <div class="vehicle-label">1ère Immatriculation</div>
                <div class="vehicle-value">{{ $contract->first_registration_date ? \Carbon\Carbon::parse($contract->first_registration_date)->format('d.m.Y') : '04.04.2017' }}</div>
            </div>
            <div class="vehicle-row">
                <div class="vehicle-label">Kilométrage :</div>
                <div class="vehicle-value">{{ number_format($contract->mileage ?? 100000, 0, ' ', ' ') }}</div>
            </div>
            <div class="vehicle-row">
                <div class="vehicle-label">Numéro de Chassis :</div>
                <div class="vehicle-value">{{ $contract->chassis_number ?? 'KNAJX81FFG7012073' }}</div>
            </div>
            <div class="vehicle-row">
                <div class="vehicle-label">Couleur:</div>
                <div class="vehicle-value">{{ $contract->color ?? 'Blanc' }}</div>
            </div>
            <div class="vehicle-row">
                <div class="vehicle-label">N° de plaques:</div>
                <div class="vehicle-value">{{ $contract->plate_number ?? '' }}</div>
            </div>
            
            <div class="checkbox-section">
                <div style="margin-bottom: 5px;">
                    <span>Accidenté</span>
                    <span class="checkbox {{ ($contract->has_accident ?? false) ? 'checked' : '' }}"></span> OUI
                    <span class="checkbox {{ !($contract->has_accident ?? false) ? 'checked' : '' }}"></span> NON
                </div>
            </div>
            
            <div class="price-section">
                <div class="price-row">
                    <div class="price-label"><span class="underline">Prix de vente TVA inclus:</span></div>
                    <div class="price-value"><span class="underline">{{ number_format($contract->sale_price ?? 6900, 2, '.', ' ') }} CHF</span></div>
                </div>
                <div class="price-row">
                    <div class="price-label">Expertisée le</div>
                    <div class="price-value">{{ $contract->expertise_date ? \Carbon\Carbon::parse($contract->expertise_date)->format('d.m.Y') : '' }}</div>
                </div>
                <div style="margin: 8px 0;">
                    <span class="checkbox {{ ($contract->expertise_same_day ?? true) ? 'checked' : '' }}"></span> Du jour
                </div>
                
                <div class="price-row">
                    <div class="price-label">Acompte ou Reprise:</div>
                    <div class="price-value">{{ number_format($contract->deposit ?? 0, 2, '.', ' ') }} CHF</div>
                </div>
                <div class="price-row">
                    <div class="price-label">Reste à Payer:</div>
                    <div class="price-value">{{ number_format(($contract->sale_price ?? 6900) - ($contract->deposit ?? 0), 2, '.', ' ') }} CHF</div>
                </div>
                
                <div style="margin: 8px 0;">
                    <div>Conditions de paiement:</div>
                    <div style="margin-top: 5px;">
                        <span class="checkbox {{ ($contract->payment_condition ?? 'cash') === 'cash' ? 'checked' : '' }}"></span> CASH<br>
                        <span class="checkbox {{ ($contract->payment_condition ?? '') === 'leasing' ? 'checked' : '' }}"></span> Leasing ou Crédit selon contrat séparé
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="warranty-section">
        <div class="warranty-header">Conditions de garantie:</div>
        <div class="warranty-content">
            <div class="warranty-option">
                <span class="checkbox {{ ($contract->warranty ?? '') === 'no_warranty' ? 'checked' : '' }}"></span>
                <span style="color: #c41e3a; font-weight: bold;">Sans Garantie/Pour Export</span>
            </div>
            <div class="warranty-option">
                <span class="checkbox {{ ($contract->warranty ?? '') === 'quality_1_qbase' ? 'checked' : '' }}"></span>
                <span style="color: #c41e3a; font-weight: bold;">Quality1 Qbase /Contrat séparé</span>
            </div>
            <div class="warranty-option">
                <span class="checkbox {{ ($contract->warranty ?? '') === 'quality_1_q3' ? 'checked' : '' }}"></span>
                <span style="color: #c41e3a; font-weight: bold;">Quality1 Q3</span>
                <span class="checkbox {{ ($contract->warranty ?? '') === 'quality_1_q5' ? 'checked' : '' }}"></span>
                <span style="color: #c41e3a; font-weight: bold;">Quality1 Q5 Contre supplément de</span> ___________
            </div>
        </div>
    </div>

    <div class="declaration">
        Le vendeur déclare que le véhicule mentionné ci-dessus est sa propriété, libre de toute engagement qu'il n'est ni investi,<br>
        ni mis en gage, ni sujet à aucun leasing et qu'il n'est pas inscrit dans le registre de réserve de propriete
    </div>

    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-date">
                <strong>Pour {{ config('app.name', 'KL AUTOMOBILES') }}:</strong>
            </div>
            <div class="signature-line"></div>
        </div>
        <div class="signature-right">
            <div class="signature-date">
                <strong>Acheteur:</strong>
            </div>
            <div class="signature-line"></div>
        </div>
    </div>

    <div class="text-center" style="margin-top: 30px;">
        <strong>{{ config('app.city', 'Crissier') }}, le : {{ \Carbon\Carbon::now()->format('d.m.Y') }}</strong>
    </div>
</body>
</html>