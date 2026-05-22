@extends('layouts.app')

@section('title', 'Historique des Transactions')
@section('page_title', 'Historique des Transactions')
@section('page_subtitle', 'Gérez et analysez vos opérations passées avec précision.')

@section('content')
<div class="max-w-[1100px] mx-auto pb-12 pt-4">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row justify-end gap-4 mb-8">
        <!-- Export & Add Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('transactions.exportExcel', request()->query()) }}" class="w-full sm:w-auto justify-center flex items-center gap-2.5 px-6 py-3.5 bg-[#e2e8f0]/60 hover:bg-[#e2e8f0]/90 text-[#334155] rounded-[14px] text-[0.95rem] font-extrabold transition-all border border-transparent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Exporter
            </a>
            @if(!auth()->user() || !auth()->user()->isAdmin())
            <a href="{{ route('transactions.create') }}" class="w-full sm:w-auto justify-center flex items-center gap-2.5 px-6 py-3.5 bg-[#0d9488] hover:bg-[#0f766e] text-white rounded-[14px] text-[0.95rem] font-extrabold shadow-md shadow-teal-500/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Transaction
            </a>
            @endif
        </div>
    </div>

    <!-- Filters Row -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-5 mb-6">
        <!-- Tab Toggle -->
        <div class="flex items-center bg-[#f1f5f9]/80 p-1.5 rounded-[16px] border border-slate-100 w-full md:w-auto">
            <a href="{{ route('transactions.index') }}" class="flex-1 text-center px-5 py-2.5 rounded-[12px] {{ !request('type') ? 'bg-white text-[#0f172a] shadow-[0_2px_10px_rgba(0,0,0,0.04)]' : 'text-slate-500 hover:text-slate-800' }} text-[0.9rem] font-extrabold transition-all border border-transparent">Tout</a>
            <a href="{{ route('transactions.index', ['type' => 'entrée']) }}" class="flex-1 text-center px-5 py-2.5 rounded-[12px] {{ request('type') == 'entrée' ? 'bg-white text-[#0f172a] shadow-[0_2px_10px_rgba(0,0,0,0.04)]' : 'text-slate-500 hover:text-slate-800' }} text-[0.9rem] font-extrabold transition-all border border-transparent">Entrées</a>
            <a href="{{ route('transactions.index', ['type' => 'sortie']) }}" class="flex-1 text-center px-5 py-2.5 rounded-[12px] {{ request('type') == 'sortie' ? 'bg-white text-[#0f172a] shadow-[0_2px_10px_rgba(0,0,0,0.04)]' : 'text-slate-500 hover:text-slate-800' }} text-[0.9rem] font-extrabold transition-all border border-transparent">Sorties</a>
        </div>

        <!-- Dropdowns -->
        <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col sm:flex-row flex-wrap items-center gap-4 flex-1 w-full md:w-auto" id="filterForm">
            @if(request('type'))
            <input type="hidden" name="type" value="{{ request('type') }}">
            @endif
            
            <div class="relative flex-1 min-w-[160px]">
                <select name="date_filtre" class="w-full appearance-none bg-[#f1f5f9]/80 border border-slate-100 rounded-[14px] px-5 pl-12 py-3.5 text-[0.9rem] font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Date</option>
                    <option value="today" {{ request('date_filtre') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('date_filtre') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('date_filtre') == 'month' ? 'selected' : '' }}>Ce mois</option>
                </select>
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>

            <div class="relative flex-1 min-w-[180px]">
                <select name="categorie_id" class="w-full appearance-none bg-[#f1f5f9]/80 border border-slate-100 rounded-[14px] px-5 pl-12 py-3.5 text-[0.9rem] font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Catégorie</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10h3v7H4zM10.5 10h3v7h-3zM2 19h20v3H2zM17 10h3v7h-3zM12 1L2 6v2h20V6z"/></svg>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>

            @if(auth()->user() && auth()->user()->isAdmin())
            <div class="relative flex-1 min-w-[180px]">
                <select name="user_id" class="w-full appearance-none bg-[#f1f5f9]/80 border border-slate-100 rounded-[14px] px-5 pl-12 py-3.5 text-[0.9rem] font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Utilisateur</option>
                </select>
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>
            @endif
            @if(request()->anyFilled(['type', 'categorie_id', 'date_filtre', 'user_id']))
                <div class="w-full sm:w-auto flex justify-center">
                    <a href="{{ route('transactions.index') }}" class="text-[0.8rem] font-extrabold text-slate-400 hover:text-red-500 transition-colors uppercase tracking-widest pl-2">Effacer</a>
                </div>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-[#f8fafc]/30 rounded-[24px] shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden mb-8">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse bg-white">
                <thead>
                    <tr class="bg-[#f8fafc]/50">
                        <th class="px-8 py-6 text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.15em] w-48 border-b-2 border-slate-100 whitespace-nowrap">Date</th>
                        <th class="py-6 text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.15em] border-b-2 border-slate-100 whitespace-nowrap">Description</th>
                        <th class="hidden md:table-cell py-6 text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.15em] border-b-2 border-slate-100 whitespace-nowrap">Catégorie</th>
                        <th class="hidden lg:table-cell py-6 text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.15em] border-b-2 border-slate-100 whitespace-nowrap">Utilisateur</th>
                        <th class="py-6 text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.15em] border-b-2 border-slate-100 whitespace-nowrap">Statut</th>
                        <th class="px-8 py-6 text-right text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.15em] border-b-2 border-slate-100 whitespace-nowrap">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors last:border-0 relative group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="font-medium text-[#334155] text-[0.95rem]">{{ $t->created_at->format('d/m/Y à H:i') }}</div>
                        </td>
                        <td class="py-6 min-w-[250px] whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-[#e0f2fe]/60 rounded-[12px] flex items-center justify-center text-[#0284c7]">
                                    @if($t->type == 'entrée')
                                    <svg class="w-5 h-5 text-[#0d9488]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                    <svg class="w-5 h-5 text-[#475569]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @endif
                                </div>
                                <span class="font-extrabold text-[#0f172a] text-[0.95rem] line-clamp-1">{{ $t->libelle }}</span>
                            </div>
                        </td>
                        <td class="hidden md:table-cell py-6 whitespace-nowrap">
                            @php
                                $catNom = $t->categorie->nom ?? 'Général';
                                if($t->type == 'entrée') { $catClass = "bg-[#ccfbf1] text-[#0f766e]"; }
                                else { $catClass = "bg-[#f1f5f9] text-[#475569]"; }
                            @endphp
                            <span class="inline-flex px-3 py-1.5 text-[0.55rem] font-extrabold uppercase tracking-widest rounded-md {{ $catClass }}">{{ mb_strimwidth($catNom, 0, 15, '...') }}</span>
                        </td>
                        <td class="hidden lg:table-cell py-6 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#334155] text-white flex items-center justify-center font-black text-[11px] shadow-sm">
                                    {{ strtoupper(substr($t->user->name ?? 'M', 0, 1)) }}
                                </div>
                                <div class="text-[0.9rem] font-bold text-slate-600 truncate max-w-[120px]">{{ $t->user->name ?? 'Utilisateur' }}</div>
                            </div>
                        </td>
                        <td class="py-6 whitespace-nowrap">
                            @php
                                $statutClass = match($t->statut) {
                                    'approuvé' => 'bg-green-50 text-green-600 border-green-100',
                                    'en_attente' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'rejeté' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-[0.55rem] font-black uppercase tracking-widest rounded-full border {{ $statutClass }}">
                                {{ str_replace('_', ' ', $t->statut ?? 'approuvé') }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right relative whitespace-nowrap">
                            <div class="font-extrabold text-[1.05rem] {{ $t->type == 'entrée' ? 'text-[#0d9488]' : 'text-[#dc2626]' }} {{ $t->statut == 'rejeté' ? 'line-through opacity-50' : '' }}">
                                {{ $t->type == 'entrée' ? '+ ' : '- ' }} {{ number_format($t->montant, 2, ',', ' ') }} F
                            </div>
                            
                            <!-- Actions Hover -->
                            @if(auth()->user() && auth()->user()->isAdmin())
                            <div class="absolute right-[110px] top-1/2 -translate-y-1/2 flex gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0 bg-white shadow-[0_4px_15px_rgba(0,0,0,0.1)] rounded-xl p-1.5 z-10">
                                @if($t->statut === 'en_attente')
                                <form action="{{ route('transactions.approve', $t->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-all" title="Approuver la dépense">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('transactions.reject', $t->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all" title="Rejeter la dépense">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                                <div class="w-px h-6 bg-slate-200 mx-1 my-auto"></div>
                                @endif
                                <a href="{{ route('transactions.edit', $t->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 hover:bg-[#0d9488] hover:text-white flex items-center justify-center transition-all" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all" onclick="return confirm('Confirmer la suppression ?')" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold bg-white">
                            Aucune transaction trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination area -->
            @if($transactions->hasPages())
            <div class="bg-[#f8fafc]/40 p-5 border-t border-slate-100 flex justify-between items-center px-8">
                <div class="text-[0.8rem] font-bold text-slate-500">
                    Affichage de <span class="font-black text-slate-800">{{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }}</span> sur <span class="font-black text-slate-800">{{ $transactions->total() }}</span> transactions
                </div>
                <!-- Custom Pagination Links -->
                <div class="flex items-center gap-1.5">
                    <a href="{{ $transactions->previousPageUrl() }}" class="text-[0.8rem] font-bold text-slate-400 hover:text-[#0d9488] px-3 py-1.5 transition-colors {{ $transactions->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">‹ Précédent</a>
                    
                    @if($transactions->lastPage() > 1)
                        @for($i = max(1, $transactions->currentPage() - 2); $i <= min($transactions->lastPage(), $transactions->currentPage() + 2); $i++)
                        <a href="{{ $transactions->url($i) }}" class="w-7 h-7 flex items-center justify-center rounded-[6px] text-[0.8rem] font-extrabold transition-all {{ $i == $transactions->currentPage() ? 'bg-[#0d9488] text-white shadow-md' : 'text-slate-600 hover:bg-slate-200' }}">
                            {{ $i }}
                        </a>
                        @endfor
                    @endif

                    <a href="{{ $transactions->nextPageUrl() }}" class="text-[0.8rem] font-bold text-[#0d9488] hover:text-[#0f766e] px-3 py-1.5 transition-colors flex items-center gap-1 {{ !$transactions->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">Suivant ›</a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Bottom KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Dépenses Ce Mois -->
        <div class="bg-white rounded-[20px] p-7 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-100 flex justify-between items-center">
            <div>
                <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">DÉPENSES CE MOIS</p>
                <div class="text-[1.4rem] font-black text-[#dc2626] tracking-tight">{{ number_format($monthTotalExpenses, 2, ',', ' ') }} F</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#fee2e2]/60 text-[#dc2626] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            </div>
        </div>

        <!-- Épargne Totale -->
        <div class="bg-white rounded-[20px] p-7 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-100 flex justify-between items-center">
            <div>
                <p class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">ÉPARGNE TOTALE</p>
                <div class="text-[1.4rem] font-black text-[#0d9488] tracking-tight">{{ number_format($epargneTotale, 2, ',', ' ') }} F</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#f0fdfa] text-[#0d9488] flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4H6v10h12V11h-2zM10 7a2 2 0 114 0v4h-4V7zm2 10a2 2 0 110-4 2 2 0 010 4z"/></svg> 
            </div>
        </div>

        <!-- Score Financier -->
        <div class="bg-[#0d9488] rounded-[20px] p-7 shadow-lg shadow-teal-500/30 flex justify-between items-center text-white relative overflow-hidden">
            <div class="relative z-10 w-full">
                <p class="text-[0.65rem] font-black text-teal-100 uppercase tracking-widest mb-1.5">SCORE FINANCIER</p>
                <div class="flex justify-between items-center w-full">
                    <div class="text-[1.3rem] font-black tracking-tight">Excellent (840)</div>
                    <div class="w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center relative z-10">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>
            </div>
            
            <!-- Abstract decor -->
            <svg class="absolute right-0 top-0 text-white/5 w-40 h-40 transform translate-x-12 -translate-y-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
    </div>
</div>
@endsection
