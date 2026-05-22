@extends('layouts.app')

@section('title', 'Projets')
@section('page_title', 'Gestion des Projets')
@section('page_subtitle', 'Gérez vos projets clients et suivez leurs paiements')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-3">
        <!-- Filtres potentiels ici plus tard -->
    </div>
    <a href="{{ route('projects.create') }}" class="px-5 py-2.5 bg-[#0f766e] hover:bg-[#0d9488] text-white font-bold rounded-xl shadow-sm text-[0.85rem] transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
        Nouveau Projet
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Client / Contact</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500">Description</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-right">Budget</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-right">Reste à payer</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-center">Statut</th>
                    <th class="py-3 px-5 text-[0.7rem] font-extrabold uppercase tracking-wider text-slate-500 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($projects as $project)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="py-3 px-5">
                        <div class="text-[0.85rem] font-bold text-slate-800">{{ $project->client_name }}</div>
                        <div class="text-[0.75rem] text-slate-500">{{ $project->client_contact ?? 'Aucun contact' }}</div>
                    </td>
                    <td class="py-3 px-5 max-w-xs truncate text-[0.85rem] text-slate-600">
                        {{ $project->description ?? '-' }}
                    </td>
                    <td class="py-3 px-5 text-right font-bold text-[0.85rem] text-slate-800">
                        {{ number_format($project->budget, 0, ',', ' ') }} F
                    </td>
                    <td class="py-3 px-5 text-right">
                        @if($project->reste_a_payer > 0)
                            <span class="text-[0.85rem] font-bold text-orange-600">{{ number_format($project->reste_a_payer, 0, ',', ' ') }} F</span>
                        @else
                            <span class="text-[0.85rem] font-bold text-green-600">0 F</span>
                        @endif
                    </td>
                    <td class="py-3 px-5 text-center">
                        @php
                            $isLate = $project->deadline && \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($project->deadline)->startOfDay()) && $project->statut !== 'solde';
                        @endphp
                        @if($project->statut == 'solde')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.7rem] font-bold bg-green-100 text-green-700">Soldé</span>
                        @elseif($isLate)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.7rem] font-bold bg-red-100 text-red-700 animate-pulse">En retard</span>
                        @elseif($project->statut == 'en_cours')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.7rem] font-bold bg-amber-100 text-amber-700">En cours</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.7rem] font-bold bg-slate-100 text-slate-700">{{ ucfirst($project->statut) }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <!-- View Button -->
                            <a href="{{ route('projects.show', $project->id) }}" class="p-1.5 text-slate-400 hover:text-[#0f766e] hover:bg-teal-50 rounded-lg transition-colors" title="Voir les détails">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            
                            @if(auth()->user() && auth()->user()->isAdmin())
                            <!-- Edit Button -->
                            <a href="{{ route('projects.edit', $project->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <!-- Delete Button -->
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet et toutes ses transactions ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center">
                        <div class="w-16 h-16 bg-[#0f766e]/10 text-[#0f766e] rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-[0.95rem] font-bold text-slate-700 mb-1">Aucun projet</h3>
                        <p class="text-[0.8rem] text-slate-500 max-w-sm mx-auto mb-4">Vous n'avez pas encore créé de projet. Commencez par ajouter votre premier client.</p>
                        <a href="{{ route('projects.create') }}" class="inline-flex px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-[0.8rem] transition-colors">
                            Nouveau Projet
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($projects->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $projects->links() }}
    </div>
    @endif
</div>

@endsection
