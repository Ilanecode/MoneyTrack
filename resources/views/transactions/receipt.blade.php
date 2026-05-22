<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu - {{ $project->client_name }} - {{ $transaction->date }}</title>
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
            font-size: 16px;
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
        .highlight-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .highlight-title {
            color: #166534;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .highlight-amount {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
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
            <h2 style="margin:0; color:#334155; font-size:24px;">REÇU DE VERSEMENT</h2>
            <div style="margin-top: 10px; font-weight:bold;">Date : {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</div>
            <div>Reçu N° : {{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div>Projet N° : {{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="client-section">
        <div style="font-size: 12px; color: #64748b; font-weight:bold; text-transform:uppercase; margin-bottom: 5px;">Reçu de</div>
        <div class="client-name">{{ $project->client_name }}</div>
        @if($project->client_contact)
            <div>Contact : {{ $project->client_contact }}</div>
        @endif
        <div style="margin-top: 10px; color: #475569;">
            <strong>Projet :</strong> {{ $project->description ?? 'Service tel que convenu.' }}
        </div>
        @if($project->deadline)
        <div style="margin-top: 5px; color: #b45309; font-weight: bold;">
            Délai du projet : {{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}
        </div>
        @endif
    </div>

    <div class="highlight-box">
        <div class="highlight-title">Montant Versé ({{ $transaction->mode_paiement }})</div>
        <div class="highlight-amount">{{ number_format($transaction->montant, 0, ',', ' ') }} F</div>
        <div style="margin-top: 5px; color: #166534; font-size: 13px;">
            Motif : {{ $transaction->libelle }}
        </div>
    </div>

    <div style="font-weight:bold; margin-bottom: 10px; color: #334155;">Situation du projet (après ce versement) :</div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Budget Total du Projet</td>
                <td class="text-right">{{ number_format($project->budget, 0, ',', ' ') }} F</td>
            </tr>
            <tr>
                <td>Total des versements effectués (y compris celui-ci)</td>
                <td class="text-right" style="color: #0f766e; font-weight:bold;">{{ number_format($total_entrees, 0, ',', ' ') }} F</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Reste à payer :</span>
            <span class="total-value">{{ number_format($reste_a_payer, 0, ',', ' ') }} F</span>
        </div>
        <div class="total-row" style="border: none; padding-top: 15px; margin-top: 15px;">
            <span class="total-label">Statut du projet :</span>
            <span class="total-value" style="text-align: right;">
                @if($project->statut == 'solde' || $reste_a_payer <= 0)
                    <span class="status-badge status-solde">Soldé</span>
                @else
                    <span class="status-badge status-encours">En cours</span>
                @endif
            </span>
        </div>
    </div>
    <div class="clear"></div>

    <div class="footer">
        Reçu généré automatiquement le {{ date('d/m/Y à H:i') }}.<br>
        Signature / Cachet (Réservé à l'administration)
    </div>

</body>
</html>
