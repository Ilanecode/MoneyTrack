<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $query = Transaction::query();

        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $startOfWeek = Carbon::now()->startOfWeek();

        // Statistiques du jour
        $entreesJour = (clone $query)->where('type', 'entrée')
            ->where('statut', 'approuvé')
            ->whereDate('date', $today)
            ->sum('montant');
        
        $sortiesJour = (clone $query)->where('type', 'sortie')
            ->where('statut', 'approuvé')
            ->whereDate('date', $today)
            ->sum('montant');

        // Solde actuel (Total Entrées - Total Sorties)
        $totalEntrees = (clone $query)->where('type', 'entrée')->where('statut', 'approuvé')->sum('montant');
        $totalSorties = (clone $query)->where('type', 'sortie')->where('statut', 'approuvé')->sum('montant');
        $solde = $totalEntrees - $totalSorties;

        // Données pour le graphique (Semaine en cours)
        $joursSemaine = ['LUN', 'MAR', 'MER', 'JEU', 'VEN', 'SAM', 'DIM'];
        $chartData = [];
        $maxSpending = 0;

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $startOfWeek)->addDays($i);
            $amount = (clone $query)->where('type', 'sortie')
                ->where('statut', 'approuvé')
                ->whereDate('date', $day)
                ->sum('montant');
            
            $chartData[] = [
                'label' => $joursSemaine[$i],
                'amount' => $amount,
                'isToday' => $day->isToday(),
            ];

            if ($amount > $maxSpending) $maxSpending = $amount;
        }

        // Calculer la hauteur relative des barres (max 180px pour le design)
        foreach ($chartData as &$data) {
            $data['height'] = $maxSpending > 0 ? ($data['amount'] / $maxSpending) * 180 : 5;
        }

        // Dernières opérations (10) sans les dépenses de projet
        $dernieresTransactions = (clone $query)->with(['categorie', 'user'])
            ->where(function ($q) {
                $q->whereNull('project_id')->orWhere('type', '!=', 'sortie');
            })
            ->latest('date')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Calculer la croissance (vs mois dernier)
        $endOfLastMonth = (clone $today)->subMonth()->endOfMonth();
        $incomeUntilLastMonth = (clone $query)->where('type', 'entrée')->where('statut', 'approuvé')->whereDate('date', '<=', $endOfLastMonth)->sum('montant');
        $expenseUntilLastMonth = (clone $query)->where('type', 'sortie')->where('statut', 'approuvé')->whereDate('date', '<=', $endOfLastMonth)->sum('montant');
        $soldeLastMonth = $incomeUntilLastMonth - $expenseUntilLastMonth;
        
        $growth = 0;
        if ($soldeLastMonth > 0) {
            $growth = (($solde - $soldeLastMonth) / $soldeLastMonth) * 100;
        } elseif ($soldeLastMonth == 0 && $solde > 0) {
            $growth = 100;
        }

        return view('dashboard', compact(
            'entreesJour',
            'sortiesJour',
            'solde',
            'growth',
            'dernieresTransactions',
            'chartData'
        ));
    }
}
