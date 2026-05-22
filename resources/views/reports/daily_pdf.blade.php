<!DOCTYPE html>
<html>
<head>
    <title>Rapport Journalier - {{ $today->format('d/m/Y') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .header h1 { color: #0f766e; margin-bottom: 5px; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MoneyTrack - Rapport de Caisse</h1>
        <p>Date : {{ $today->format('d/m/Y') }}</p>
    </div>

    <div class="stats">
        <p><strong>Total Entrées :</strong> {{ number_format($totalEntrees, 0, ',', ' ') }} FCFA</p>
        <p><strong>Total Sorties :</strong> {{ number_format($totalSorties, 0, ',', ' ') }} FCFA</p>
        <p><strong>Solde du jour :</strong> <span class="{{ $solde >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($solde, 0, ',', ' ') }} FCFA</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->libelle }}</td>
                <td>{{ $t->categorie ? $t->categorie->nom : 'N/A' }}</td>
                <td>{{ ucfirst($t->type) }}</td>
                <td style="font-weight: bold; color: {{ $t->type == 'entrée' ? 'green' : 'red' }}">
                    {{ number_format($t->montant, 0, ',', ' ') }}
                </td>
                <td>{{ $t->created_at->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y H:i') }} par {{ auth()->user()->name }}
    </div>
</body>
</html>
