<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - {{ $project->client_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 20px;
        }
        .company-details {
            float: left;
            width: 50%;
        }
        .invoice-details {
            float: right;
            width: 50%;
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
            margin-bottom: 5px;
        }
        .clear {
            clear: both;
        }
        .client-section {
            margin-bottom: 40px;
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
        }
        .client-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        th {
            background-color: #0f766e;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            width: 50%;
            float: right;
        }
        .total-row {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .total-row:last-child {
            border-bottom: none;
            font-size: 18px;
            font-weight: bold;
            color: #0f766e;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #0f766e;
        }
        .total-label {
            display: inline-block;
            width: 60%;
            font-weight: bold;
        }
        .total-value {
            display: inline-block;
            width: 38%;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .status-solde {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-encours {
            background-color: #fef3c7;
            color: #b45309;
        }
        .footer {
            margin-top: 80px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-details">
            <div class="company-name">MoneyTrack</div>
            <div>Facturation & Gestion de Projets</div>
        </div>
        <div class="invoice-details">
            <h2 style="margin:0; color:#334155; font-size:28px;">FACTURE</h2>
            <div style="margin-top: 10px; font-weight:bold;">Date : {{ date('d/m/Y') }}</div>
            <div>Projet N° : {{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div style="margin-top: 10px;">
                Statut : 
                @if($project->statut == 'solde')
                    <span class="status-badge status-solde">Soldé</span>
                @else
                    <span class="status-badge status-encours">En cours</span>
                @endif
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="client-section">
        <div style="font-size: 12px; color: #64748b; font-weight:bold; text-transform:uppercase; margin-bottom: 5px;">Facturé à</div>
        <div class="client-name">{{ $project->client_name }}</div>
        @if($project->client_contact)
            <div>Contact : {{ $project->client_contact }}</div>
        @endif
        <div style="margin-top: 10px; color: #475569;">
            <strong>Description du projet :</strong><br>
            {{ $project->description ?? 'Service tel que convenu.' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Prestation globale pour le projet</td>
                <td class="text-right">{{ number_format($project->budget, 0, ',', ' ') }} F</td>
            </tr>
        </tbody>
    </table>

    @if($project->transactions->count() > 0)
        <div style="font-weight:bold; margin-bottom: 10px; color: #334155;">Détail des acomptes / paiements reçus :</div>
        <table>
            <thead>
                <tr>
                    <th style="background-color: #64748b;">Date</th>
                    <th style="background-color: #64748b;">Libellé</th>
                    <th style="background-color: #64748b;">Mode</th>
                    <th class="text-right" style="background-color: #64748b;">Montant Versé</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->transactions as $paiement)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($paiement->date)->format('d/m/Y') }}</td>
                    <td>{{ $paiement->libelle }}</td>
                    <td>{{ ucfirst($paiement->mode_paiement) }}</td>
                    <td class="text-right" style="color: #0f766e; font-weight:bold;">
                        {{ number_format($paiement->montant, 0, ',', ' ') }} F
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Montant Total (Devis) :</span>
            <span class="total-value">{{ number_format($project->budget, 0, ',', ' ') }} F</span>
        </div>
        <div class="total-row">
            <span class="total-label">Déjà payé (Acomptes) :</span>
            <span class="total-value" style="color: #0f766e;">- {{ number_format($entrees, 0, ',', ' ') }} F</span>
        </div>
        <div class="total-row">
            <span class="total-label">Reste à payer :</span>
            <span class="total-value">{{ number_format($reste_a_payer, 0, ',', ' ') }} F</span>
        </div>
    </div>
    <div class="clear"></div>

    <div class="footer">
        Document généré automatiquement le {{ date('d/m/Y à H:i') }}.<br>
        Merci de votre confiance !
    </div>

</body>
</html>
