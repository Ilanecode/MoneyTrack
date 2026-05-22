@extends('layouts.app')

@section('title', 'Détails du Projet')
@section('page_title', 'Projet : ' . $project->client_name)
@section('page_subtitle', 'Suivi des revenus, dépenses et rentabilité')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('projects.index') }}" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 font-bold rounded-xl text-[0.85rem] transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Retour aux projets
    </a>
    
    <div class="flex gap-2">
        @if(auth()->user()->isAdmin())
            <!-- Edit Button -->
            <a href="{{ route('projects.edit', $project->id) }}" class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-[0.8rem] font-bold transition-colors flex items-center gap-1" title="Modifier le projet">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span class="hidden sm:inline">Modifier</span>
            </a>
            <!-- Delete Button -->
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet et toutes ses transactions ? Cette action est irréversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-[0.8rem] font-bold transition-colors flex items-center gap-1" title="Supprimer le projet">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span class="hidden sm:inline">Supprimer</span>
                </button>
            </form>
        @endif
        @if(auth()->user()->isAdmin() && $project->statut === 'solde')
        <a href="{{ route('projects.invoice', $project) }}" class="px-3 py-1 bg-slate-800 text-white rounded-full text-[0.8rem] font-bold flex items-center gap-1 hover:bg-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Facture PDF
        </a>
        @endif
        @php
            $isLate = $project->deadline && \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($project->deadline)->startOfDay()) && $project->statut !== 'solde';
            $whatsappMessage = urlencode("Bonjour {$project->client_name}, sauf erreur de notre part, le paiement de votre projet est arrivé à échéance le " . \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') . ". Le reste à payer est de " . number_format($project->reste_a_payer, 0, ',', ' ') . " F. Merci de nous contacter pour régulariser la situation.");
        @endphp

        @if($isLate && $project->client_contact)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $project->client_contact) }}?text={{ $whatsappMessage }}" target="_blank" class="px-3 py-1 bg-green-500 text-white rounded-full text-[0.8rem] font-bold flex items-center gap-1 hover:bg-green-600 transition-colors animate-pulse shadow-sm shadow-green-500/30">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.6c-1.637 0-3.235-.417-4.646-1.209l-5.174 1.356 1.38-5.044c-.876-1.464-1.338-3.14-1.338-4.869 0-5.414 4.406-9.821 9.82-9.821 5.414 0 9.822 4.407 9.822 9.821 0 5.414-4.408 9.821-9.822 9.821z"/></svg>
            Relancer
        </a>
        @endif

        @if($project->statut === 'solde')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.8rem] font-bold bg-green-100 text-green-700">Soldé</span>
        @elseif($isLate)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.8rem] font-bold bg-red-100 text-red-700 animate-pulse border border-red-200">En retard</span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[0.8rem] font-bold bg-amber-100 text-amber-700">En cours</span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Résumé Financier -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:col-span-2">
        <h3 class="text-lg font-bold text-[#0f766e] mb-4 pb-2 border-b border-slate-100">Bilan Financier</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-50 rounded-xl p-4">
                <div class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-wider mb-1">Budget Total</div>
                <div class="text-[1.1rem] font-black text-slate-800">{{ number_format($project->budget, 0, ',', ' ') }} F</div>
            </div>
            <div class="bg-teal-50 rounded-xl p-4">
                <div class="text-[0.7rem] font-bold text-teal-600 uppercase tracking-wider mb-1">Encaissé</div>
                <div class="text-[1.1rem] font-black text-teal-700">{{ number_format($entrees, 0, ',', ' ') }} F</div>
            </div>
            <div class="bg-red-50 rounded-xl p-4">
                <div class="text-[0.7rem] font-bold text-red-600 uppercase tracking-wider mb-1">Dépensé (Matériel)</div>
                <div class="text-[1.1rem] font-black text-red-700">{{ number_format($sorties, 0, ',', ' ') }} F</div>
            </div>
            <div class="bg-orange-50 rounded-xl p-4">
                <div class="text-[0.7rem] font-bold text-orange-600 uppercase tracking-wider mb-1">Reste à Payer</div>
                <div class="text-[1.1rem] font-black text-orange-700">{{ number_format($project->reste_a_payer, 0, ',', ' ') }} F</div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-slate-100 flex justify-center">
            <div class="flex items-center gap-4 bg-slate-800 text-white px-8 py-4 rounded-2xl shadow-md border border-slate-700">
                <div class="text-[0.95rem] font-bold text-slate-300">Bénéfice du projet :</div>
                <div class="text-[1.4rem] font-black tracking-tight {{ $benefice >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $benefice > 0 ? '+' : '' }}{{ number_format($benefice, 0, ',', ' ') }} F
                </div>
            </div>
        </div>
    </div>

    <!-- Infos Client -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-[#0f766e] mb-4 pb-2 border-b border-slate-100">Informations Client</h3>
        <div class="space-y-4">
            <div>
                <div class="text-[0.75rem] font-bold text-slate-500 mb-0.5">Nom complet</div>
                <div class="text-[0.9rem] font-semibold text-slate-800">{{ $project->client_name }}</div>
            </div>
            <div>
                <div class="text-[0.75rem] font-bold text-slate-500 mb-0.5">Contact</div>
                <div class="text-[0.9rem] font-semibold text-slate-800">{{ $project->client_contact ?? 'Non renseigné' }}</div>
            </div>
            <div>
                <div class="text-[0.75rem] font-bold text-slate-500 mb-0.5">Description du service</div>
                <div class="text-[0.85rem] text-slate-600 bg-slate-50 p-3 rounded-lg mt-1">
                    {{ $project->description ?? 'Aucune description.' }}
                </div>
            </div>
            <div>
                <div class="text-[0.75rem] font-bold text-slate-500 mb-0.5">Date de création</div>
                <div class="text-[0.85rem] font-semibold text-slate-800">{{ $project->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des Dépenses Liées -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="p-4 border-b border-slate-100 bg-slate-50/50">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4">
            <h3 class="text-lg font-bold text-[#0f766e]">Liste des Dépenses Matériel</h3>
        </div>
        
        {{-- Formulaire d'ajout rapide (Caissier uniquement) --}}
        @if(auth()->user() && auth()->user()->isCaissier())
        <style>
            .depense-form input[type="text"],
            .depense-form input[type="number"] {
                background-color: #ffffff !important;
                color: #0f172a !important;
                -webkit-text-fill-color: #0f172a !important;
                border: 2px solid #64748b !important;
                font-size: 16px !important;
                font-weight: 700 !important;
                padding: 12px 16px !important;
                height: 48px !important;
                border-radius: 10px !important;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.06) !important;
            }
            .depense-form input[type="text"]:focus,
            .depense-form input[type="number"]:focus {
                border-color: #0d9488 !important;
                outline: none !important;
                box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.25) !important;
            }
            .depense-form input::placeholder {
                color: #94a3b8 !important;
                -webkit-text-fill-color: #94a3b8 !important;
                font-weight: 500 !important;
            }
        </style>
        <form action="{{ route('transactions.store') }}" method="POST" class="depense-form bg-white p-5 rounded-xl border-2 border-teal-700 shadow-md">
            @csrf
            <input type="hidden" name="type" value="sortie">
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="mode_paiement" value="Cash">
            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
            
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[0.8rem] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Désignation du matériel</label>
                    <input type="text" name="libelle" placeholder="Ex: Ciment, Fer à béton, Peinture..." required>
                </div>
                
                <div class="w-full md:w-56">
                    <label class="block text-[0.8rem] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Montant (F CFA)</label>
                    <div class="relative">
                        <input type="number" name="montant" placeholder="0" step="1" min="1" required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-extrabold text-slate-500 text-base pointer-events-none">F</span>
                    </div>
                </div>
                
                <button type="submit" class="w-full md:w-auto px-8 py-3 h-[48px] bg-[#0f766e] hover:bg-[#0d9488] text-white font-extrabold rounded-xl text-[0.9rem] transition-all flex items-center justify-center gap-2 whitespace-nowrap shadow-lg shadow-teal-600/20 hover:shadow-teal-600/30 hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Ajouter
                </button>
            </div>
        </form>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-100">
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Date</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Motif (Matériel)</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-center">Statut</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-right">Montant</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-center w-auto">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @php
                    $depenses = $project->transactions->where('type', 'sortie');
                @endphp
                
                @forelse($depenses as $depense)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-5 text-[0.85rem] font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-5 text-[0.85rem] font-bold text-slate-800">
                        {{ $depense->libelle }}
                    </td>
                    <td class="py-3 px-5 text-center">
                        @php
                            $statutClass = match($depense->statut) {
                                'approuvé' => 'bg-green-50 text-green-600 border-green-100',
                                'en_attente' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'rejeté' => 'bg-red-50 text-red-600 border-red-100',
                                default => 'bg-slate-50 text-slate-600 border-slate-200'
                            };
                        @endphp
                        <span class="inline-flex px-2 py-1 text-[0.6rem] font-black uppercase tracking-widest rounded-full border {{ $statutClass }}">
                            {{ str_replace('_', ' ', $depense->statut ?? 'approuvé') }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-right font-bold text-[0.85rem] text-red-600">
                        -{{ number_format($depense->montant, 0, ',', ' ') }} F
                    </td>
                    <td class="py-3 px-5 text-center whitespace-nowrap">
                        @if(auth()->user() && auth()->user()->isAdmin() && $depense->statut === 'en_attente')
                            <form action="{{ route('transactions.approve', $depense->id) }}" method="POST" class="inline-block mr-1">
                                @csrf
                                <button type="submit" class="text-amber-500 hover:text-green-600 transition-colors p-1" title="Approuver">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('transactions.reject', $depense->id) }}" method="POST" class="inline-block mr-2 border-r border-slate-200 pr-2">
                                @csrf
                                <button type="submit" class="text-amber-500 hover:text-red-600 transition-colors p-1" title="Rejeter">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('transactions.destroy', $depense) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression de cette dépense ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-500 text-[0.85rem]">
                        Aucune dépense enregistrée pour ce projet. Utilisez le formulaire ci-dessus.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Liste des Encaissements -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-4 border-b border-slate-100 bg-slate-50/50">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4">
            <h3 class="text-lg font-bold text-[#0f766e]">Paiements Reçus (Cash/Avances)</h3>
        </div>
        
        @if($project->statut !== 'solde')
        {{-- Formulaire d'encaissement rapide --}}
        <form action="{{ route('transactions.store') }}" method="POST" class="depense-form bg-white p-5 rounded-xl border-2 border-teal-200 shadow-md">
            @csrf
            <input type="hidden" name="type" value="entrée">
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="categorie_id" value="">
            
            <div class="grid grid-cols-1 md:grid-cols-[1fr_130px_160px_auto] gap-3 items-end">
                <div>
                    <label class="block text-[0.8rem] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Motif du paiement</label>
                    <input type="text" name="libelle" placeholder="Ex: 2ème Tranche, Solde final..." required>
                </div>
                
                <div>
                    <label class="block text-[0.8rem] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Mode</label>
                    <select name="mode_paiement" required style="background-color: #ffffff !important; color: #0f172a !important; -webkit-text-fill-color: #0f172a !important; border: 2px solid #64748b !important; font-size: 14px !important; font-weight: 700 !important; padding: 10px 12px !important; height: 48px !important; border-radius: 10px !important; width: 100%;">
                        <option value="Cash">Cash</option>
                        <option value="Mobile Money">MoMo</option>
                        <option value="Chèque">Chèque</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-[0.8rem] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Montant (F CFA)</label>
                    <div class="relative">
                        <input type="number" name="montant" placeholder="0" step="1" max="{{ $project->reste_a_payer }}" min="1" required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-extrabold text-slate-500 text-base pointer-events-none">F</span>
                    </div>
                </div>
                
                <button type="submit" class="px-5 py-3 h-[48px] bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-xl text-[0.85rem] transition-all flex items-center justify-center gap-2 whitespace-nowrap shadow-lg shadow-teal-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Encaisser
                </button>
            </div>
        </form>
        @else
        <div class="text-[0.85rem] font-bold text-green-600 bg-green-50 p-3 rounded-xl border border-green-100 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Le projet est entièrement soldé. Aucun paiement supplémentaire n'est requis.
        </div>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-100">
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Date</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Libellé</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Mode</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-right">Montant</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-center">Reçu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @php
                    $paiements = $project->transactions->where('type', 'entrée');
                @endphp
                
                @forelse($paiements as $paiement)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-5 text-[0.85rem] font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($paiement->date)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-5 text-[0.85rem] font-bold text-slate-800">
                        {{ $paiement->libelle }}
                    </td>
                    <td class="py-3 px-5 text-[0.8rem] text-slate-600">
                        {{ $paiement->mode_paiement }}
                    </td>
                    <td class="py-3 px-5 text-right font-bold text-[0.85rem] text-teal-600">
                        +{{ number_format($paiement->montant, 0, ',', ' ') }} F
                    </td>
                    <td class="py-3 px-5 text-center">
                        <a href="{{ route('transactions.receipt', $paiement) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors" title="Imprimer le reçu">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-500 text-[0.85rem]">
                        Aucun paiement enregistré pour ce projet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
