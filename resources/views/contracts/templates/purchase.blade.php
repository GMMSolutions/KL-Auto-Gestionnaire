<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>KL Automobiles - Contrat d'Achat - {{ $contract->id }}</title>
    <style>
        @page {
            margin: 25px 35px 20px 35px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11.5px;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 26px;
            font-weight: bold;
            color: #000;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
            margin-top: 0px;
        }

        .company-details {
            font-size: 12px;
            margin-bottom: 5px;
            font-weight: normal;
        }

        .company-subinfo {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            margin-bottom: 8px;
        }

        .company-subinfo td {
            padding: 0;
        }

        .contract-title {
            font-size: 12.5px;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 8px;
            background-color: #e6e6e6;
            padding: 3px 6px;
            border-bottom: 1.5px solid #000;
            border-top: 1px solid #000;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 6px;
        }

        .info-table td {
            font-size: 11px;
            padding: 3px 4px;
            vertical-align: top;
        }

        .info-table td.label-col {
            width: 32%;
        }

        .info-table td.val-col {
            width: 68%;
        }

        .declaration-text {
            font-size: 10.5px;
            line-height: 1.35;
            margin: 15px 0 10px 0;
            text-align: justify;
        }

        .signature-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .signature-table td {
            padding: 0;
            font-size: 11.5px;
            vertical-align: top;
        }

        .signature-table .col-date {
            text-align: left;
            width: 38%;
        }

        .signature-table .col-vendeur {
            text-align: left;
            width: 31%;
        }

        .signature-table .col-acheteur {
            text-align: left;
            width: 31%;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name', 'KL AUTOMOBILES SA') }}</div>
        <div class="company-details">
            Route de Bussigny 22 - 1023 Crissier - +41 79 500 67 67
        </div>
        <table class="company-subinfo">
            <tr>
                <td style="text-align: left; width: 40%;">CHE-109.519.355 TVA</td>
                <td style="text-align: right; width: 60%;">IBAN CH90 0900 0000 1770 9550 0</td>
            </tr>
        </table>
    </div>

    <div class="contract-title">CONTRAT D'ACHAT D'UN VEHICULE D'OCCASION</div>

    <table class="info-table">
        <tr>
            <td class="label-col" style="font-weight: bold; text-decoration: underline;">Vendeur</td>
            <td class="val-col"></td>
        </tr>
        <tr>
            <td class="label-col">Nom, Prénom</td>
            <td class="val-col">{{ $contract->buyer_surname ?? '' }} {{ $contract->buyer_name ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Date de naissance</td>
            <td class="val-col">
                {{ $contract->buyer_birth_date ? \Carbon\Carbon::parse($contract->buyer_birth_date)->format('d.m.Y') : '' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">Adresse (Rue, Numéro)</td>
            <td class="val-col">{{ $contract->buyer_address ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Code Postal / Ville</td>
            <td class="val-col">{{ $contract->buyer_zip ?? '' }} {{ $contract->buyer_city ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">N° de Téléphone</td>
            <td class="val-col">{{ $contract->buyer_phone ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Email</td>
            <td class="val-col">{{ $contract->buyer_email ?? '' }}</td>
        </tr>
    </table>

    <div class="contract-title">OBJET DU CONTRAT</div>

    <table class="info-table">
        <tr>
            <td class="label-col">Marque et Type</td>
            <td class="val-col" style="font-weight: bold;">{{ $contract->vehicle_brand ?? '' }} {{ $contract->vehicle_type ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">1ère Immatriculation</td>
            <td class="val-col">
                {{ $contract->first_registration_date ? \Carbon\Carbon::parse($contract->first_registration_date)->format('d.m.Y') : '' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">Kilométrage</td>
            <td class="val-col">
                {{ $contract->mileage ? number_format($contract->mileage, 0, '.', ' ') : '________' }} km (selon compteur)
            </td>
        </tr>
        <tr>
            <td class="label-col">Numéro de chassis</td>
            <td class="val-col">{{ $contract->chassis_number ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Couleur</td>
            <td class="val-col">{{ $contract->color ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">N° de plaques</td>
            <td class="val-col">{{ $contract->plate_number ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Accidenté</td>
            <td class="val-col">{{ isset($contract->has_accident) ? ($contract->has_accident ? 'Oui' : 'Non') : '________' }} (à la connaissance du vendeur)</td>
        </tr>
        <tr>
            <td class="label-col">Prix d'achat TVA incluse</td>
            <td class="val-col" style="font-weight: bold;">CHF {{ $contract->sale_price !== null ? number_format($contract->sale_price, 2, ',', ' ') : '________' }}</td>
        </tr>
        <tr>
            <td class="label-col">Expertisée le</td>
            <td class="val-col">
                {{ $contract->expertise_date ? \Carbon\Carbon::parse($contract->expertise_date)->format('d.m.Y') : '' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">Remarques</td>
            <td class="val-col">{{ $contract->remarques ?? '' }}</td>
        </tr>
    </table>

    <div class="declaration-text">
        Le vendeur déclare que le véhicule mentionné ci-dessus est sa propriété, libre de tout engagement, qu'il n'est ni investi, ni mis en gage, ni sujet à aucun leasing et qu'il n'est pas inscrit dans le registre de réserve de propriété.
    </div>

    <table class="signature-table">
        <tr>
            <td class="col-date">{{ config('app.city', 'Crissier') }}, le {{ $contract->created_at ? $contract->created_at->format('d.m.Y') : '________' }}</td>
            <td class="col-vendeur">Vendeur :</td>
            <td class="col-acheteur">Acheteur :</td>
        </tr>
    </table>
</body>

</html>