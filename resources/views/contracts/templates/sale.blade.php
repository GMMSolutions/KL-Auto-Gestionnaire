<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>KL Automobiles - Contrat de Vente - {{ $contract->id }}</title>
    <style>
        @page {
            margin: 20px 32px 18px 32px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.28;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 6px;
        }

        .company-name {
            font-size: 25px;
            font-weight: bold;
            color: #000;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
            margin-top: 0px;
        }

        .company-details {
            font-size: 11.5px;
            margin-bottom: 4px;
            font-weight: normal;
        }

        .company-subinfo {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .company-subinfo td {
            padding: 0;
        }

        .contract-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 5px;
            background-color: #e6e6e6;
            padding: 2.5px 6px;
            border-bottom: 1.5px solid #000;
            border-top: 1px solid #000;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 3px;
        }

        .info-table td {
            font-size: 10.5px;
            padding: 2px 4px;
            vertical-align: top;
        }

        .info-table td.label-col {
            width: 32%;
        }

        .info-table td.val-col {
            width: 68%;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 2px;
        }

        .section-text {
            font-size: 9.5px;
            line-height: 1.26;
            text-align: justify;
            margin-bottom: 4px;
        }

        .warranty-section {
            border: 1px solid #000;
            margin: 5px 0;
            padding: 5px 7px;
        }

        .warranty-header-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2.5px;
        }

        .warranty-desc {
            font-size: 9.5px;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .warranty-option {
            margin: 2px 0;
            font-size: 9.5px;
            line-height: 1.25;
        }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1.2px solid #000;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 10px;
            font-size: 9px;
            font-weight: bold;
            margin-right: 5px;
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        .paraphe-box {
            text-align: right;
            font-size: 9.5px;
            margin-top: 3px;
            margin-bottom: 4px;
        }

        .declaration-text {
            font-size: 9.5px;
            line-height: 1.25;
            margin-bottom: 3px;
            text-align: justify;
        }

        .signature-table {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
        }

        .signature-table td {
            padding: 0;
            font-size: 10.5px;
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

        /* Page 2 - CGV */
        .page-break {
            page-break-before: always;
        }

        .cgv-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .cgv-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .cgv-subtitle {
            font-size: 10.5px;
            margin-bottom: 3px;
        }

        .cgv-version {
            font-size: 9.5px;
            margin-bottom: 10px;
        }

        .cgv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .cgv-col {
            vertical-align: top;
            font-size: 9.2px;
            line-height: 1.28;
            text-align: justify;
        }

        .cgv-article-title {
            font-weight: bold;
            font-size: 9.8px;
            margin-top: 5px;
            margin-bottom: 2px;
        }

        .cgv-article-p {
            margin-top: 0;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <!-- PAGE 1: CONTRAT DE VENTE -->
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

    <div class="contract-title">CONTRAT DE VENTE D'UN VEHICULE D'OCCASION</div>

    <table class="info-table">
        <tr>
            <td class="label-col" style="font-weight: bold; text-decoration: underline;">Acheteur</td>
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
            <td class="label-col">Prix de vente TVA incluse</td>
            <td class="val-col" style="font-weight: bold;">CHF {{ $contract->sale_price !== null ? number_format($contract->sale_price, 2, ',', ' ') : '________' }}</td>
        </tr>
        <tr>
            <td class="label-col">Expertisée le</td>
            <td class="val-col">
                {{ $contract->expertise_date ? \Carbon\Carbon::parse($contract->expertise_date)->format('d.m.Y') : '' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">Arrhes versées ce jour</td>
            <td class="val-col">CHF {{ $contract->deposit !== null && $contract->deposit > 0 ? number_format($contract->deposit, 2, ',', ' ') : '________' }}</td>
        </tr>
        <tr>
            <td class="label-col">Reste à payer</td>
            <td class="val-col" style="font-weight: bold;">CHF {{ $contract->sale_price !== null ? number_format(max(0, $contract->sale_price - ($contract->deposit ?? 0)), 2, ',', ' ') : '________' }}</td>
        </tr>
        <tr>
            <td class="label-col">Conditions de paiement</td>
            <td class="val-col">{{ $contract->payment_condition ?? '________' }} ; solde payable au plus tard le ________ et dans tous les cas avant la remise du véhicule</td>
        </tr>
        <tr>
            <td class="label-col">Remarques</td>
            <td class="val-col">{{ $contract->remarques ?? '' }}</td>
        </tr>
    </table>

    <div class="section-title">Arrhes</div>
    <div class="section-text">
        L'acheteur verse ce jour des arrhes de CHF {{ $contract->deposit !== null && $contract->deposit > 0 ? number_format($contract->deposit, 2, ',', ' ') : '________' }} à titre de dédit au sens de l'art. 158 al. 3 CO. Si la vente est exécutée, elles sont imputées sur le prix.<br>
        Si l'acheteur renonce à l'achat, ou si le solde n'est pas payé à l'échéance convenue, le vendeur peut se départir du contrat sans autre formalité (art. 214 CO). Les arrhes lui restent alors acquises et il dispose librement du véhicule. Si le vendeur renonce à la vente, il restitue à l'acheteur le double des arrhes.
    </div>

    <div class="warranty-section">
        <div class="warranty-header-title">Conditions de garantie</div>
        <div class="warranty-desc">
            La garantie est fournie exclusivement par Quality1 AG, selon un contrat de garantie séparé remis à l'acheteur. Seules les conditions de ce contrat font foi quant à l'étendue, la durée et les exclusions de la couverture.
        </div>
        <div class="warranty-option">
            <span class="checkbox">{!! ($contract->warranty ?? '') === 'quality_1_qbase' ? 'X' : '&nbsp;' !!}</span> Quality1 Qbase, comprise dans le prix (moteur et boîte de vitesses : 12 mois ou 20 000 km)
        </div>
        <div class="warranty-option">
            <span class="checkbox">{!! ($contract->warranty ?? '') === 'quality_1_q3' ? 'X' : '&nbsp;' !!}</span> Quality1 Q3, contre supplément de CHF {{ ($contract->warranty === 'quality_1_q3' && !empty($contract->warranty_amount)) ? number_format($contract->warranty_amount, 2, ',', ' ') : '________' }}
        </div>
        <div class="warranty-option">
            <span class="checkbox">{!! ($contract->warranty ?? '') === 'quality_1_q5' ? 'X' : '&nbsp;' !!}</span> Quality1 Q5, contre supplément de CHF {{ ($contract->warranty === 'quality_1_q5' && !empty($contract->warranty_amount)) ? number_format($contract->warranty_amount, 2, ',', ' ') : '________' }}
        </div>
        <div class="warranty-option">
            <span class="checkbox">{!! ($contract->warranty ?? '') === 'no_warranty' ? 'X' : '&nbsp;' !!}</span> Sans garantie
        </div>
        <div class="warranty-option">
            <span class="checkbox">{!! ($contract->warranty ?? '') === 'no_warranty_export' ? 'X' : '&nbsp;' !!}</span> Sans garantie (véhicule destiné à l'exportation)
        </div>
    </div>

    <div class="section-title">Exclusion de garantie</div>
    <div class="section-text">
        Le véhicule est vendu d'occasion, dans l'état où il se trouve au jour de la remise, état que l'acheteur déclare connaître après l'avoir examiné et essayé.<br>
        En dehors de la garantie Quality1 choisie ci-dessus, toute garantie du vendeur pour les défauts du véhicule (art. 197 ss CO) est exclue. Est également exclue toute autre prétention envers le vendeur liée à l'état du véhicule, quel qu'en soit le fondement, notamment en réduction du prix, en résolution de la vente ou en dommages-intérêts. Les demandes couvertes par la garantie Quality1 sont adressées directement à Quality1 AG.<br>
        Le vendeur reste tenu des obligations prévues au présent contrat, notamment celles mentionnées sous « Remarques ». Demeurent réservés les défauts que le vendeur aurait frauduleusement dissimulés (art. 199 CO) ainsi que sa responsabilité pour dol ou faute grave (art. 100 al. 1 CO).
    </div>

    <div class="paraphe-box">
        <strong>Paraphe de l'acheteur :</strong> ________
    </div>

    <div class="declaration-text">
        Le vendeur déclare que le véhicule mentionné ci-dessus est sa propriété, libre de tout engagement, qu'il n'est ni investi, ni mis en gage, ni sujet à aucun leasing et qu'il n'est pas inscrit dans le registre de réserve de propriété.
    </div>
    <div class="declaration-text">
        L'acheteur déclare avoir reçu, lu et accepté les conditions générales de vente de KL Automobiles SA (au verso), qui font partie intégrante du présent contrat.
    </div>

    <table class="signature-table">
        <tr>
            <td class="col-date">{{ config('app.city', 'Crissier') }}, le {{ $contract->created_at ? $contract->created_at->format('d.m.Y') : '________' }}</td>
            <td class="col-vendeur">Vendeur :</td>
            <td class="col-acheteur">Acheteur :</td>
        </tr>
    </table>

    <!-- PAGE 2: CONDITIONS GÉNÉRALES DE VENTE -->
    <div class="page-break"></div>

    <div class="cgv-header">
        <div class="cgv-title">CONDITIONS GÉNÉRALES DE VENTE</div>
        <div class="cgv-subtitle">Véhicules d'occasion - KL Automobiles SA, Route de Bussigny 22, 1023 Crissier</div>
        <div class="cgv-version">Version du ________</div>
    </div>

    <table class="cgv-table">
        <tr>
            <!-- Left Column (Articles 1 to 12) -->
            <td class="cgv-col" style="width: 48.5%; padding-right: 10px;">
                <div class="cgv-article-title">1. Champ d'application</div>
                <div class="cgv-article-p">
                    Les présentes conditions générales s'appliquent à toute vente de véhicule d'occasion par KL Automobiles SA (ci-après « le vendeur »). Elles font partie intégrante du contrat de vente. En cas de contradiction, le contrat de vente l'emporte.
                </div>

                <div class="cgv-article-title">2. Conclusion du contrat</div>
                <div class="cgv-article-p">
                    Le contrat est conclu par la signature des deux parties. Toute modification ou tout accord complémentaire doit figurer par écrit dans le contrat pour être valable.
                </div>

                <div class="cgv-article-title">3. Prix et paiement</div>
                <div class="cgv-article-p">
                    Les prix s'entendent en francs suisses, TVA incluse. Les frais d'immatriculation, de plaques et d'assurance ne sont pas compris. Le solde est réglé par virement bancaire et doit être crédité sur le compte du vendeur avant la remise du véhicule. Un paiement en espèces n'est accepté que contre quittance signée par le vendeur.
                </div>

                <div class="cgv-article-title">4. Financement et leasing</div>
                <div class="cgv-article-p">
                    Lorsque l'acheteur finance l'achat par un crédit ou un leasing, la demande est transmise par l'intermédiaire du vendeur. L'acheteur fournit sans délai les documents demandés et des informations exactes et complètes. En cas de refus de l'organisme de financement, le contrat devient caduc et le vendeur dispose librement du véhicule. Les arrhes sont alors restituées, sauf si le refus résulte d'indications inexactes ou incomplètes de l'acheteur ou de documents non fournis.
                </div>

                <div class="cgv-article-title">5. Reprise d'un véhicule</div>
                <div class="cgv-article-p">
                    Le véhicule repris est estimé sur la base des déclarations de l'acheteur (accidents, kilométrage, défauts connus). L'acheteur garantit qu'il en est propriétaire et que ce véhicule n'est grevé d'aucun gage, leasing ou réserve de propriété. Il le remet au plus tard lors de la livraison, avec le permis de circulation, toutes les clés et les documents d'entretien. Si l'état du véhicule ne correspond pas aux déclarations, ou s'il a subi un dommage depuis l'estimation, le vendeur peut adapter la valeur de reprise ou refuser la reprise ; la différence est alors payée en argent.
                </div>

                <div class="cgv-article-title">6. Remise du véhicule</div>
                <div class="cgv-article-p">
                    Le véhicule est remis à l'acheteur au garage du vendeur à Crissier, après paiement intégral du prix. L'acheteur vient le chercher dans les 10 jours qui suivent l'avis de mise à disposition. Passé ce délai, des frais de garde de CHF ____.– par jour peuvent être facturés, et le vendeur peut se départir du contrat conformément à la clause sur les arrhes.
                </div>

                <div class="cgv-article-title">7. Risques et propriété</div>
                <div class="cgv-article-p">
                    Les profits et les risques passent à l'acheteur lors de la remise du véhicule. Le véhicule, ses clés et ses documents ne sont remis qu'après paiement intégral ; jusque-là, le vendeur en reste propriétaire.
                </div>

                <div class="cgv-article-title">8. Immatriculation et assurance</div>
                <div class="cgv-article-p">
                    L'immatriculation, les plaques et l'assurance responsabilité civile sont à la charge de l'acheteur. Les plaques professionnelles du vendeur ne servent qu'aux essais et ne sont pas prêtées pour la prise en charge. Si le vendeur effectue les démarches d'immatriculation à la demande de l'acheteur, ses frais peuvent être facturés.
                </div>

                <div class="cgv-article-title">9. Expertise</div>
                <div class="cgv-article-p">
                    La mention d'une expertise atteste que le véhicule a passé le contrôle officiel à la date indiquée. Elle ne constitue pas une garantie de l'absence de défauts.
                </div>

                <div class="cgv-article-title">10. Kilométrage et historique</div>
                <div class="cgv-article-p">
                    Le kilométrage indiqué est celui du compteur. Les indications sur l'historique du véhicule reposent sur les documents dont dispose le vendeur et sur les informations de l'ancien détenteur.
                </div>

                <div class="cgv-article-title">11. Vérification et garantie</div>
                <div class="cgv-article-p">
                    L'acheteur vérifie le véhicule lors de la remise et signale immédiatement par écrit tout défaut apparent. La garantie est réglée exclusivement par le contrat de vente et, le cas échéant, par le contrat de garantie Quality1 choisi. En cas de panne, l'acheteur avise Quality1 AG avant toute réparation et suit la procédure prévue par ce contrat ; une réparation engagée sans accord préalable peut ne pas être prise en charge.
                </div>

                <div class="cgv-article-title">12. Accessoires et travaux</div>
                <div class="cgv-article-p">
                    Seuls les équipements et accessoires mentionnés dans le contrat sont compris dans le prix. Les travaux ou accessoires commandés en plus font l'objet d'une facture séparée.
                </div>
            </td>

            <!-- Spacer -->
            <td style="width: 3%;"></td>

            <!-- Right Column (Articles 13 to 16) -->
            <td class="cgv-col" style="width: 48.5%; padding-left: 10px;">
                <div class="cgv-article-title">13. Vente à l'exportation</div>
                <div class="cgv-article-p">
                    Lorsque le véhicule est vendu pour l'exportation, l'acheteur s'engage à l'exporter et à remettre au vendeur la preuve du dédouanement dans les 30 jours. Il supporte les formalités et taxes liées à l'exportation. Si le prix a été fixé hors TVA en vue de l'exportation et que cette preuve n'est pas remise dans le délai, la TVA est due en sus.
                </div>

                <div class="cgv-article-title">14. Protection des données</div>
                <div class="cgv-article-p">
                    Le vendeur traite les données de l'acheteur pour l'exécution du contrat et le respect de ses obligations légales. Il peut les transmettre à Quality1 AG pour la garantie, à l'organisme de financement et aux autorités compétentes, dans la mesure nécessaire.
                </div>

                <div class="cgv-article-title">15. Nullité partielle</div>
                <div class="cgv-article-p">
                    Si une clause du contrat ou des présentes conditions devait être nulle, la validité des autres n'en serait pas affectée.
                </div>

                <div class="cgv-article-title">16. Droit applicable et for</div>
                <div class="cgv-article-p">
                    Le contrat est soumis au droit suisse, à l'exclusion de la Convention de Vienne sur la vente internationale de marchandises. Le for exclusif est au siège du vendeur à Crissier, sous réserve des fors impératifs prévus par la loi.
                </div>
            </td>
        </tr>
    </table>
</body>

</html>