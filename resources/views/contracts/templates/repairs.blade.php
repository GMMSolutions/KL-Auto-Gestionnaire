<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>KL Automobiles - Réparations - {{ $vehicle->vehicle_brand ?? '' }} {{ $vehicle->vehicle_type ?? '' }}</title>
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
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 40px;
            font-weight: bold;
            color: #c41e3a;
            letter-spacing: 2px;
            margin-bottom: 8px;
            margin-top: 0px;
        }

        .company-details {
            font-size: 15.5px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .company-details-underline {
            font-size: 15.5px;
            margin-bottom: 5px;
            margin-top: 15.5px;
        }

        .contract-title {
            font-size: 15.5px;
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
            margin: 15.5px 0;
            font-size: 15.5px;
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
            margin: 15.5px 0;
            line-height: 1.3;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .signature-left, .signature-right {
            width: 45%;
        }

        .signature-date {
            margin-bottom: 0px;
        }

        td {
            font-size: 15.5px;
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

    <div class="contract-title">Réparations : {{ $vehicle->vehicle_brand ?? '' }} {{ $vehicle->vehicle_type ?? '' }} - {{ $vehicle->chassis_number ?? '' }} </div>
    
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <colgroup>
            <col style="width: 65%;">
            <col style="width: 35%;">
        </colgroup>
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 4px 8px; text-align: left;">Description</th>
                <th style="border: 1px solid #000; padding: 4px 8px; text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @if($repairs->count() > 0)
                @foreach($repairs as $repair)
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px 8px;">{{ $repair->description }}</td>
                        <td style="border: 1px solid #000; padding: 4px 8px; text-align: right;">CHF {{ number_format($repair->amount, 2, '.', "'") }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="border: 1px solid #000; padding: 4px 8px; text-align: left; font-weight: bold;">Total</td>
                    <td style="border: 1px solid #000; padding: 4px 8px; text-align: right; font-weight: bold;">CHF {{ number_format($total, 2, '.', "'") }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="2" style="border: 1px solid #000; padding: 4px 8px; text-align: center;">Aucune réparation enregistrée</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>