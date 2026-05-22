<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export Transactions</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0;
            padding: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f1f5f9;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-success { color: #10b981; font-weight: bold; }
        .text-danger { color: #ef4444; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Money Track</h1>
        <p>Historique des Transactions</p>
        <p style="font-size: 10px;">Date d'export : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Type</th>
                <th>Mode / Réf</th>
                <th>Enregistré Par</th>
                <th class="text-right">Montant (F)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalEntrees = 0;
                $totalSorties = 0;
            @endphp
            @foreach($transactions as $t)
                @php 
                    if($t->type == 'entrée') $totalEntrees += $t->montant;
                    else $totalSorties += $t->montant;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                    <td>{{ $t->libelle }}</td>
                    <td>{{ $t->categorie ? $t->categorie->nom : 'N/A' }}</td>
                    <td>
                        <span class="{{ $t->type == 'entrée' ? 'text-success' : 'text-danger' }}">
                            {{ ucfirst($t->type) }}
                        </span>
                    </td>
                    <td>{{ $t->mode_paiement }}<br><small style="color: #888;">{{ $t->type == 'entrée' ? $t->source : $t->beneficiaire }}</small></td>
                    <td>{{ $t->user ? $t->user->name : 'N/A' }}</td>
                    <td class="text-right">
                        {{ number_format($t->montant, 2, ',', ' ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right" style="font-weight: bold;">TOTAL DES ENTRÉES :</td>
                <td class="text-right text-success">{{ number_format($totalEntrees, 2, ',', ' ') }} F</td>
            </tr>
            <tr>
                <td colspan="6" class="text-right" style="font-weight: bold;">TOTAL DES SORTIES :</td>
                <td class="text-right text-danger">{{ number_format($totalSorties, 2, ',', ' ') }} F</td>
            </tr>
            <tr>
                <td colspan="6" class="text-right" style="font-weight: bold; background: #f1f5f9;">BALANCE PÉRIODE :</td>
                <td class="text-right" style="font-weight: bold; background: #f1f5f9; color: {{ ($totalEntrees - $totalSorties) >= 0 ? '#10b981' : '#ef4444' }};">
                    {{ number_format($totalEntrees - $totalSorties, 2, ',', ' ') }} F
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Généré par Money Track v2.0 Premium
    </div>

</body>
</html>
