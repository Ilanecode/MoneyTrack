<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #0d9488; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #0f766e; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 14px; }
        .stats { margin-bottom: 30px; display: table; width: 100%; text-align: center; }
        .stat-box { display: table-cell; width: 33%; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .stat-value { font-size: 18px; font-weight: bold; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 12px 10px; text-align: left; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; }
        .text-success { color: #0d9488; }
        .text-danger { color: #dc2626; }
        .footer { margin-top: 40px; font-size: 10px; text-align: center; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MoneyTrack</h1>
        <p>{{ $title }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div style="color: #64748b; font-size: 11px; text-transform: uppercase;">Total Entrées</div>
            <div class="stat-value text-success">+ {{ number_format($totalEntrees, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="stat-box">
            <div style="color: #64748b; font-size: 11px; text-transform: uppercase;">Total Sorties</div>
            <div class="stat-value text-danger">- {{ number_format($totalSorties, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="stat-box">
            <div style="color: #64748b; font-size: 11px; text-transform: uppercase;">Solde Net</div>
            <div class="stat-value {{ $solde >= 0 ? 'text-success' : 'text-danger' }}">
                {{ number_format($solde, 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                <td><strong>{{ $t->libelle }}</strong></td>
                <td>{{ $t->categorie ? $t->categorie->nom : 'N/A' }}</td>
                <td style="text-align: right; font-weight: bold;" class="{{ $t->type == 'entrée' ? 'text-success' : 'text-danger' }}">
                    {{ $t->type == 'entrée' ? '+' : '-' }} {{ number_format($t->montant, 0, ',', ' ') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">Aucune transaction sur cette période.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} par {{ auth()->user()->name }}
    </div>
</body>
</html>
