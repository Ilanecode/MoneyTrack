<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $now = \Carbon\Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        
        $prevMonthStart = $now->copy()->subMonth()->startOfMonth();
        $prevMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // Requête de base pour les filtres
        $baseQuery = Transaction::where('statut', 'approuvé');
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $baseQuery->where('user_id', auth()->id());
        }

        // Totaux du mois en cours
        $currentMonthTransactions = (clone $baseQuery)->whereBetween('date', [$startOfMonth, $endOfMonth])->get();
        $totalIncome = $currentMonthTransactions->where('type', 'entrée')->sum('montant');
        $totalExpenses = $currentMonthTransactions->where('type', 'sortie')->sum('montant');
        $profit = $totalIncome - $totalExpenses;

        // Totaux du mois précédent
        $prevMonthTransactions = (clone $baseQuery)->whereBetween('date', [$prevMonthStart, $prevMonthEnd])->get();
        $prevIncome = $prevMonthTransactions->where('type', 'entrée')->sum('montant');
        $prevExpenses = $prevMonthTransactions->where('type', 'sortie')->sum('montant');

        // Variations
        $percentageIncome = $prevIncome > 0 ? (($totalIncome - $prevIncome) / $prevIncome) * 100 : 0;
        $percentageExpenses = $prevExpenses > 0 ? (($totalExpenses - $prevExpenses) / $prevExpenses) * 100 : 0;

        // Répartition par catégorie (Sorties)
        $categoryBreakdown = (clone $baseQuery)->with('categorie')
            ->where('type', 'sortie')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('categorie_id, SUM(montant) as total')
            ->groupBy('categorie_id')
            ->get()
            ->map(fn($item) => [
                'nom' => $item->categorie ? $item->categorie->nom : 'N/A',
                'total' => (int) $item->total
            ]);

        // Tendances mensuelles (6 derniers mois pour le graphique)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $inc = (clone $baseQuery)->where('type', 'entrée')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('montant');
            $exp = (clone $baseQuery)->where('type', 'sortie')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('montant');
            
            $monthlyTrends[] = [
                'label' => ucfirst($month->translatedFormat('M')),
                'income' => (int) $inc,
                'expense' => (int) $exp
            ];
        }

        // Bilan Mensuel Détaillé (12 derniers mois)
        $bilanAnnuel = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $now->copy()->subMonths($i);
            $inc = (clone $baseQuery)->where('type', 'entrée')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('montant');
            $exp = (clone $baseQuery)->where('type', 'sortie')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('montant');
            
            // On ne l'ajoute que si c'est le mois en cours ou s'il y a eu des transactions
            if ($i == 0 || $inc > 0 || $exp > 0) {
                $bilanAnnuel[] = [
                    'mois' => ucfirst($month->translatedFormat('F Y')),
                    'entrees' => $inc,
                    'sorties' => $exp,
                    'solde' => $inc - $exp
                ];
            }
        }

        // Transactions détaillées (pour l'historique de la vue, on affiche tout)
        $historyQuery = Transaction::query();
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $historyQuery->where('user_id', auth()->id());
        }
        $transactions = $historyQuery->with('categorie')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('reports.index', compact(
            'totalIncome',
            'totalExpenses',
            'profit',
            'percentageIncome',
            'percentageExpenses',
            'categoryBreakdown',
            'monthlyTrends',
            'bilanAnnuel',
            'transactions'
        ));
    }

    public function generateDailyReport()
    {
        $today = \Carbon\Carbon::today();
        $query = Transaction::with(['categorie', 'user'])
            ->where('statut', 'approuvé')
            ->whereDate('date', $today);
            
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->get();

        $totalEntrees = $transactions->where('type', 'entrée')->sum('montant');
        $totalSorties = $transactions->where('type', 'sortie')->sum('montant');
        $solde = $totalEntrees - $totalSorties;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.daily_pdf', compact(
            'transactions', 'totalEntrees', 'totalSorties', 'solde', 'today'
        ));

        return $pdf->download('rapport_journalier_' . $today->format('d_m_Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:daily,monthly,annual,category',
            'format' => 'required|string|in:pdf,excel',
        ]);

        $query = Transaction::with(['categorie', 'user'])->where('statut', 'approuvé');
        
        // Sécurité si caissier (même si protégée par admin, c'est mieux)
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $now = \Carbon\Carbon::now();
        $title = "Rapport des Transactions";

        if ($request->type === 'daily') {
            $query->whereDate('date', $now->today());
            $title = "Rapport Journalier - " . $now->format('d/m/Y');
        } elseif ($request->type === 'monthly') {
            $query->whereMonth('date', $now->month)->whereYear('date', $now->year);
            $title = "Rapport Mensuel - " . $now->translatedFormat('F Y');
        } elseif ($request->type === 'annual') {
            $query->whereYear('date', $now->year);
            $title = "Rapport Annuel - " . $now->year;
        } elseif ($request->type === 'category') {
            // Groupe par catégorie
            // On va récupérer tous les totaux
            return $this->exportCategorySummary($request->format, $now);
        }

        $transactions = $query->latest('date')->get();

        if ($request->format === 'pdf') {
            $totalEntrees = $transactions->where('type', 'entrée')->sum('montant');
            $totalSorties = $transactions->where('type', 'sortie')->sum('montant');
            $solde = $totalEntrees - $totalSorties;

            $pdf = Pdf::loadView('reports.pdf', compact(
                'transactions', 'totalEntrees', 'totalSorties', 'solde', 'title'
            ));
            return $pdf->download(Str::slug($title) . '.pdf');
        }

        // Export Excel (CSV)
        return $this->generateCsv($transactions, $title);
    }

    private function exportCategorySummary($format, $now)
    {
        $query = Transaction::with('categorie')->where('statut', 'approuvé');
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $title = "Rapport par Catégories - " . $now->year;

        $stats = $query->selectRaw('categorie_id, type, SUM(montant) as total')
            ->groupBy('categorie_id', 'type')
            ->get();

        // Réorganisation pour la vue
        $categoriesStats = [];
        foreach ($stats as $stat) {
            $nom = $stat->categorie ? $stat->categorie->nom : 'Non catégorisé';
            if (!isset($categoriesStats[$nom])) {
                $categoriesStats[$nom] = ['entree' => 0, 'sortie' => 0];
            }
            if ($stat->type == 'entrée') {
                $categoriesStats[$nom]['entree'] += $stat->total;
            } else {
                $categoriesStats[$nom]['sortie'] += $stat->total;
            }
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.category_pdf', compact('categoriesStats', 'title'));
            return $pdf->download(Str::slug($title) . '.pdf');
        }

        // CSV par catégorie
        $fileName = Str::slug($title) . '_' . date('Y_m_d_H_i_s') . '.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $columns = ['Catégorie', 'Total Entrées (F)', 'Total Sorties (F)', 'Solde Net (F)'];

        $callback = function() use ($categoriesStats, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');
            foreach ($categoriesStats as $nom => $data) {
                fputcsv($file, [$nom, $data['entree'], $data['sortie'], $data['entree'] - $data['sortie']], ';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    private function generateCsv($transactions, $title)
    {
        $fileName = Str::slug($title) . '_' . date('Y_m_d_H_i_s') . '.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $columns = ['Date', 'Libellé', 'Type', 'Catégorie', 'Montant', 'Mode de paiement', 'Utilisateur'];

        $callback = function() use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($transactions as $task) {
                fputcsv($file, [
                    \Carbon\Carbon::parse($task->date)->format('d/m/Y'),
                    $task->libelle,
                    ucfirst($task->type),
                    $task->categorie ? $task->categorie->nom : 'N/A',
                    $task->montant,
                    $task->mode_paiement,
                    $task->user ? $task->user->name : 'N/A'
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
