<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'modalite_paiement' => 'required|in:cash,fraction',
            'montant_avance' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $montant_paye = 0;
            if ($validated['modalite_paiement'] === 'cash') {
                $montant_paye = $validated['budget'];
            } elseif ($validated['modalite_paiement'] === 'fraction' && !empty($validated['montant_avance'])) {
                $montant_paye = $validated['montant_avance'];
            }

            $project = Project::create([
                'client_name' => $validated['client_name'],
                'client_contact' => $validated['client_contact'],
                'description' => $validated['description'],
                'budget' => $validated['budget'],
                'modalite_paiement' => $validated['modalite_paiement'],
                'deadline' => $validated['deadline'] ?? null,
                'montant_paye' => $montant_paye,
                'statut' => $montant_paye >= $validated['budget'] ? 'solde' : 'en_cours',
                'user_id' => auth()->id(),
            ]);

            // Create transaction if payment is made
            if ($montant_paye > 0) {
                Transaction::create([
                    'type' => 'entrée',
                    'montant' => $montant_paye,
                    'libelle' => 'Paiement Projet : ' . $project->client_name,
                    'mode_paiement' => 'cash',
                    'source' => 'Projet',
                    'date' => now()->toDateString(),
                    'user_id' => auth()->id(),
                    'statut' => 'approuvé', 
                    'project_id' => $project->id,
                ]);
            }

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Le projet a été créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la création : ' . $e->getMessage())->withInput();
        }
    }

    public function show(Project $project)
    {
        $project->load(['transactions' => function($query) {
            $query->latest('date');
        }]);

        $entrees = $project->transactions->where('type', 'entrée')->where('statut', 'approuvé')->sum('montant');
        $sorties = $project->transactions->where('type', 'sortie')->where('statut', 'approuvé')->sum('montant');

        $benefice = $project->budget - $sorties;

        return view('projects.show', compact('project', 'entrees', 'sorties', 'benefice'));
    }

    public function exportInvoice(Project $project)
    {
        $project->load(['transactions' => function($query) {
            $query->where('type', 'entrée')->where('statut', 'approuvé')->latest('date');
        }]);

        $entrees = $project->transactions->sum('montant');
        $reste_a_payer = $project->budget - $entrees;

        $pdf = Pdf::loadView('projects.invoice', compact('project', 'entrees', 'reste_a_payer'));
        
        return $pdf->download('Facture_' . \Illuminate\Support\Str::slug($project->client_name) . '_' . date('Ymd') . '.pdf');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'deadline' => 'nullable|date',
        ]);

        $project->update($validated);

        // Mettre à jour le statut du projet en cas de changement de budget
        $project->updateStatus();

        return redirect()->route('projects.show', $project->id)->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $project)
    {
        // Supprimer toutes les transactions liées (Option B)
        $project->transactions()->delete();
        
        // Supprimer le projet
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Le projet et toutes ses transactions ont été supprimés.');
    }
}
