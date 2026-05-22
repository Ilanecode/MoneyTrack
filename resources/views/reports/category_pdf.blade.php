<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #0d9488; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #0f172a; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 14px; }
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

    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th style="text-align: right;">Total Entrées</th>
                <th style="text-align: right;">Total Sorties</th>
                <th style="text-align: right;">Solde Net</th>
            </tr>
        </thead>
        <tbody>
            @php $globalEntree = 0; $globalSortie = 0; @endphp
            @forelse($categoriesStats as $nom => $data)
            @php 
                $solde = $data['entree'] - $data['sortie']; 
                $globalEntree += $data['entree'];
                $globalSortie += $data['sortie'];
            @endphp
            <tr>
                <td><strong>{{ $nom }}</strong></td>
                <td style="text-align: right;" class="text-success">+ {{ number_format($data['entree'], 0, ',', ' ') }}</td>
                <td style="text-align: right;" class="text-danger">- {{ number_format($data['sortie'], 0, ',', ' ') }}</td>
                <td style="text-align: right; font-weight: bold;" class="{{ $solde >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($solde, 0, ',', ' ') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">Aucune donnée enregistrée.</td>
            </tr>
            @endforelse
            
            <tr style="background-color: #f8fafc;">
                <td style="text-transform: uppercase; font-weight: black; color: #0f172a;"><strong>TOTAL GLOBAL</strong></td>
                <td style="text-align: right; font-weight: bold;" class="text-success">+ {{ number_format($globalEntree, 0, ',', ' ') }}</td>
                <td style="text-align: right; font-weight: bold;" class="text-danger">- {{ number_format($globalSortie, 0, ',', ' ') }}</td>
                <td style="text-align: right; font-weight: bold;" class="{{ ($globalEntree-$globalSortie) >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($globalEntree - $globalSortie, 0, ',', ' ') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} par {{ auth()->user()->name }}
    </div>
</body>
</html>
