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
            font-size: 36px;
            font-weight: bold;
            color: #c41e3a;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .company-details {
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .company-details-underline {
            font-size: 14px;
            margin-bottom: 5px;
            margin-top: 15px;
        }

        .contract-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 20px;
            background-color: #e6e6e6;
            padding: 2px 8px;
            border-bottom: 2px solid #000;
            border-top: 1px solid #000;
            text-align: left;
            text-align: center;
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

        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }

        .checkbox.checked::after {
            content: "x";
            font-size: 10px;
            font-weight: bold;
            display: block;
            text-align: center;
            line-height: 10px;
        }

        .declaration {
            font-size: 11px;
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

        td {
            font-size: 14px;
            padding: 4px 8px;
            vertical-align: top;
        }

    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ config('app.name', 'KL AUTOMOBILES SA') }}</div>
        <div class="company-details">
            Route de Bussigny 22 - 1023 Crissier - +41 79 500 67 67<br>
        </div>
        <div class="company-details-underline">
            <span style="float: left;">TVA .109.519.355</span> <span style="float: right;">IBAN CH90 0900 0000 1770 9550 0</span>
        </div>
    </div>

    <div class="contract-title">CONTRAT DE VENTE D'UN VEHICULE D'OCCASION</div>
    
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <colgroup>
            <col style="width: 35%;">
            <col style="width: 65%;">
        </colgroup>
        <tr>
            <td style="font-weight: bold; text-decoration: underline; padding: 4px 8px;">Acheteur</td>
            <td style="padding: 4px 8px;"></td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Nom, Prénom</td>
            <td style="padding: 4px 8px;">{{ $contract->buyer_surname ?? '' }}, {{ $contract->buyer_name ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Date de naissance</td>
            <td style="padding: 4px 8px;">{{ $contract->buyer_birth_date ? \Carbon\Carbon::parse($contract->buyer_birth_date)->format('d.m.Y') : '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Adresse (Rue, Numéro)</td>
            <td style="padding: 4px 8px;">{{ $contract->buyer_address ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Code Postal / Ville</td>
            <td style="padding: 4px 8px;">{{ $contract->buyer_zip ?? '' }} {{ $contract->buyer_city ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">N° de Téléphone</td>
            <td style="padding: 4px 8px;">{{ $contract->buyer_phone ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Email</td>
            <td style="padding: 4px 8px;">{{ $contract->buyer_email ?? '' }}</td>
        </tr>
    </table>

    <div class="contract-title">OBJET DU CONTRAT</div>

    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 10px;">
        <colgroup>
            <col style="width: 35%;">
            <col style="width: 65%;">
        </colgroup>
        <tr>
            <td style="padding: 4px 8px;">Marque et Type :</td>
            <td style="font-weight: bold;">{{ $contract->vehicle_brand ?? '' }} {{ $contract->vehicle_type ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">1ère Immatriculation :</td>
            <td style="padding: 4px 8px;">{{ $contract->first_registration_date ? \Carbon\Carbon::parse($contract->first_registration_date)->format('d.m.Y') : '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Kilométrage :</td>
            <td style="padding: 4px 8px;">{{ $contract->mileage ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Numéro de chassis :</td>
            <td style="padding: 4px 8px;">{{ $contract->chassis_number ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Couleur :</td>
            <td style="padding: 4px 8px;">{{ $contract->color ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">N° de plaques :</td>
            <td style="padding: 4px 8px;">{{ $contract->plate_number ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Accidenté :</td>
            <td style="padding: 4px 8px;">{{ $contract->has_accident ? 'Oui' : 'Non' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Prix de vente TVA inclus:</td>
            <td style="padding: 4px 8px; font-weight: bold; text-decoration: underline;">CHF {{ number_format($contract->sale_price, 2, ',', ' ') }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Expertisée le :</td>
            <td style="padding: 4px 8px;">{{ $contract->expertise_date ? \Carbon\Carbon::parse($contract->expertise_date)->format('d.m.Y') : '' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Acompte ou reprise :</td>
            <td style="padding: 4px 8px;">CHF {{ number_format($contract->deposit, 2, ',', ' ') }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Reste à payer :</td>
            <td style="padding: 4px 8px; font-weight: bold;">CHF {{ number_format($contract->sale_price - $contract->deposit, 2, ',', ' ') }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;">Conditions de paiement :</td>
            <td style="padding: 4px 8px;">{{ $contract->payment_condition ?? '' }}</td>
        </tr>
    </table>

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
            </div>
            <div class="warranty-option">
                <span class="checkbox {{ ($contract->warranty ?? '') === 'quality_1_q5' ? 'checked' : '' }}"></span>
                <span style="color: #c41e3a; font-weight: bold;">Quality1 Q5 Contre supplément de</span> <span>{{ $contract->warranty_amount ?? '' }}</span>
            </div>
        </div>
    </div>

    <div class="declaration">
        Le vendeur déclare que le véhicule mentionné ci-dessus est sa propriété, libre de toute engagement qu'il n'est ni investi,
        ni mis en gage, ni sujet à aucun leasing et qu'il n'est pas inscrit dans le registre de réserve de propriété
    </div>

    <div class="text-center" style="margin-top: 30px;">
        <strong style="font-size: 12px;">{{ config('app.city', 'Crissier') }}, le : {{ \Carbon\Carbon::now()->format('d.m.Y') }}</strong>
    </div>


    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 10px;">
        <colgroup>
            <col style="width: 35%;">
            <col style="width: 65%;">
        </colgroup>
        <tr>
            <td style="padding: 4px 8px;">Pour {{ config('app.name', 'KL AUTOMOBILES') }}:</td>
            <td style="font-weight: bold;">Acheteur:</td>
        </tr>
    </table>
</body>
</html>