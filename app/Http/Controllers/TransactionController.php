<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AppSetting;
use App\Models\Transaction;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['categorie', 'user']);

        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Masquer les dépenses de projet de la liste générale
        $query->where(function ($q) {
            $q->whereNull('project_id')->orWhere('type', '!=', 'sortie');
        });

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        $transactions = $query->latest('date')->latest('created_at')->paginate(10);
        
        $categories = Categorie::all();

        // Résumé du Mois
        $now = \Carbon\Carbon::now();
        $baseQuery = Transaction::query();
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $baseQuery->where('user_id', auth()->id());
        }

        $monthTotalExpenses = (clone $baseQuery)->where('type', 'sortie')
            ->where('statut', 'approuvé')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('montant');

        $prevMonth = $now->copy()->subMonth();
        $prevMonthTotalExpenses = (clone $baseQuery)->where('type', 'sortie')
            ->where('statut', 'approuvé')
            ->whereMonth('date', $prevMonth->month)
            ->whereYear('date', $prevMonth->year)
            ->sum('montant');

        $monthVariation = $prevMonthTotalExpenses > 0 
            ? (($monthTotalExpenses - $prevMonthTotalExpenses) / $prevMonthTotalExpenses) * 100 
            : 0;

        // Budget configurable depuis les Paramètres
        $monthlyBudget = (float) AppSetting::get('budget_mensuel', 1000000);
        $budgetUsedPercentage = $monthlyBudget > 0 ? ($monthTotalExpenses / $monthlyBudget) * 100 : 0;

        // Épargne Totale (Solde Actuel)
        $totalEntrees = (clone $baseQuery)->where('type', 'entrée')->where('statut', 'approuvé')->sum('montant');
        $totalSorties = (clone $baseQuery)->where('type', 'sortie')->where('statut', 'approuvé')->sum('montant');
        $epargneTotale = $totalEntrees - $totalSorties;

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'monthTotalExpenses',
            'monthVariation',
            'budgetUsedPercentage',
            'epargneTotale'
        ));
    }

    public function create()
    {
        $categories = \App\Models\Categorie::where('type', 'entrée')->get();
        $projects = \App\Models\Project::where('statut', 'en_cours')->get();
        return view('transactions.create', compact('categories', 'projects'));
    }

    public function createSortie()
    {
        $categories = \App\Models\Categorie::where('type', 'sortie')->get();
        $projects = \App\Models\Project::where('statut', 'en_cours')->get();
        return view('transactions.create_sortie', compact('categories', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'          => 'required|in:entrée,sortie',
            'montant'       => 'required|numeric|min:0.01',
            'libelle'       => 'required|string|max:255',
            'categorie_id'  => 'nullable|exists:categories,id',
            'mode_paiement' => 'required|string|max:50',
            'source'        => 'nullable|string|max:255',
            'beneficiaire'  => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:500',
            'date'          => 'required|date',
            'project_id'    => 'nullable|exists:projects,id',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($data['type'] == 'sortie') {
            // Vérification du solde global pour le caissier (s'applique à toutes les dépenses)
            if (!Auth::user()->isAdmin()) {
                $totalEntrees = Transaction::where('type', 'entrée')->where('statut', 'approuvé')->sum('montant');
                $totalSorties = Transaction::where('type', 'sortie')->where('statut', 'approuvé')->sum('montant');
                $soldeActuel = $totalEntrees - $totalSorties;

                if ($soldeActuel < $data['montant']) {
                    return redirect()->back()->withInput()->with('error', 'Solde insuffisant dans la caisse globale. Dépense refusée.');
                }
            }

            // Dépense liée à un projet → toujours en attente d'approbation admin
            if (!empty($data['project_id'])) {
                if (Auth::user()->isAdmin()) {
                    $data['statut'] = 'approuvé';
                    $message = 'Dépense projet enregistrée et approuvée.';
                } else {
                    $data['statut'] = 'en_attente';
                    $message = "Dépense projet envoyée à l'administrateur pour approbation.";
                }
            } else {
                // Dépense générale (hors projet)
                if (!Auth::user()->isAdmin()) {
                    $data['statut'] = 'en_attente';
                    $message = "Demande de dépense envoyée à l'administrateur pour approbation.";
                } else {
                    $data['statut'] = 'approuvé';
                    $message = 'Opération enregistrée avec succès !';
                }
            }
        } else {
            $data['statut'] = 'approuvé';
            $message = 'Opération enregistrée avec succès !';
        }

        $transaction = Transaction::create($data);

        Audit::log('création', "Transaction #{$transaction->id} créée : {$transaction->libelle} ({$transaction->montant} F) - Statut: {$data['statut']}", 'Transaction', $transaction->id);

        if (!empty($data['project_id'])) {
            return redirect()->route('projects.show', $data['project_id'])->with('success', $message);
        }

        return redirect()->route('dashboard')->with('success', $message);
    }

    public function edit(Transaction $transaction)
    {
        $categories = Categorie::all();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'type'          => 'required|in:entrée,sortie',
            'montant'       => 'required|numeric|min:0.01',
            'libelle'       => 'required|string|max:255',
            'categorie_id'  => 'nullable|exists:categories,id',
            'mode_paiement' => 'required|string|max:50',
            'source'        => 'nullable|string|max:255',
            'beneficiaire'  => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:500',
            'date'          => 'required|date',
            'project_id'    => 'nullable|exists:projects,id',
        ]);

        $data = $request->all();

        // Si un caissier modifie une dépense, elle doit repasser en attente d'approbation
        if ($data['type'] == 'sortie' && !\Illuminate\Support\Facades\Auth::user()->isAdmin()) {
            // Vérification du solde pour le caissier
            $totalEntrees = Transaction::where('type', 'entrée')->where('statut', 'approuvé')->sum('montant');
            $totalSorties = Transaction::where('type', 'sortie')->where('statut', 'approuvé')->sum('montant');
            $soldeActuel = $totalEntrees - $totalSorties;

            if ($soldeActuel < $data['montant']) {
                return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Dépense rejetée.');
            }

            $data['statut'] = 'en_attente';
            $message = 'Transaction modifiée et renvoyée pour approbation.';
        } else {
            $message = 'Transaction mise à jour avec succès !';
        }

        $transaction->update($data);

        \App\Models\Audit::log('modification', "Transaction #{$transaction->id} modifiée", 'Transaction', $transaction->id);

        return redirect()->route('transactions.index')->with('success', $message);
    }

    public function destroy(Transaction $transaction)
    {
        $id = $transaction->id;
        $libelle = $transaction->libelle;
        $transaction->delete();

        Audit::log('suppression', "Transaction #{$id} supprimée ({$libelle})", 'Transaction', $id);

        return redirect()->route('transactions.index')->with('success', 'Transaction supprimée.');
    }

    public function approve(Transaction $transaction)
    {
        $transaction->update(['statut' => 'approuvé']);
        Audit::log('approbation', "Transaction #{$transaction->id} approuvée", 'Transaction', $transaction->id);
        return redirect()->back()->with('success', 'Dépense approuvée et déduite du solde.');
    }

    public function reject(Transaction $transaction)
    {
        $transaction->update(['statut' => 'rejeté']);
        Audit::log('rejet', "Transaction #{$transaction->id} rejetée", 'Transaction', $transaction->id);
        return redirect()->back()->with('success', 'Dépense rejetée.');
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'transactions_' . date('Y_m_d_H_i_s') . '.csv';

        $query = Transaction::with(['categorie', 'user']);

        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Appliquer les mêmes filtres que pour l'index
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        $transactions = $query->latest('date')->get();

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Date', 'Libelle', 'Type', 'Categorie', 'Montant', 'Mode de paiement', 'Source/Beneficiaire', 'Enregistre par');

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            // Ajout du BOM pour forcer Excel à lire le UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($transactions as $task) {
                $row['Date']  = \Carbon\Carbon::parse($task->date)->format('d/m/Y');
                $row['Libelle'] = $task->libelle;
                $row['Type']  = ucfirst($task->type);
                $row['Categorie']  = $task->categorie ? $task->categorie->nom : 'N/A';
                $row['Montant']  = $task->montant;
                $row['Mode de paiement']  = $task->mode_paiement;
                $row['Source/Beneficiaire']  = $task->type == 'entrée' ? $task->source : $task->beneficiaire;
                $row['Enregistre par']  = $task->user ? $task->user->name : 'N/A';

                fputcsv($file, array($row['Date'], $row['Libelle'], $row['Type'], $row['Categorie'], $row['Montant'], $row['Mode de paiement'], $row['Source/Beneficiaire'], $row['Enregistre par']), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $fileName = 'transactions_' . date('Y_m_d_H_i_s') . '.pdf';

        $query = Transaction::with(['categorie', 'user']);

        if (auth()->check() && !auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        $transactions = $query->latest('date')->get();

        $pdf = Pdf::loadView('transactions.pdf', compact('transactions'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    public function exportReceipt(Transaction $transaction)
    {
        if (!$transaction->project_id || $transaction->type !== 'entrée') {
            abort(404, 'Reçu indisponible pour cette transaction.');
        }

        $project = $transaction->project;

        $project->load(['transactions' => function($query) {
            $query->where('type', 'entrée')->where('statut', 'approuvé')->latest('date');
        }]);

        $total_entrees = $project->transactions->sum('montant');
        $reste_a_payer = $project->budget - $total_entrees;

        $pdf = Pdf::loadView('transactions.receipt', compact('transaction', 'project', 'total_entrees', 'reste_a_payer'));
        
        return $pdf->download('Recu_Versement_' . $transaction->id . '_' . date('Ymd') . '.pdf');
    }
}
