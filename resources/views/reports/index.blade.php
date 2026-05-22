@extends('layouts.app')

@section('title', 'Aperçu Mensuel')
@section('breadcrumb', 'Rapports Financiers')

@section('content')
<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <!-- Revenus -->
    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 sm:p-10 shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)] group hover:-translate-y-2 transition-all duration-500">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-primary flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total des Revenus</div>
        <div class="text-3xl font-black text-slate-800 tracking-tighter mb-4">{{ number_format($totalIncome, 0, '.', ',') }} F</div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $percentageIncome >= 0 ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' }}">
            {{ $percentageIncome >= 0 ? '↑' : '↓' }} {{ abs(number_format($percentageIncome, 1)) }}% vs mois dernier
        </div>
    </div>

    <!-- Dépenses -->
    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 sm:p-10 shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)] group hover:-translate-y-2 transition-all duration-500">
        <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
        </div>
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total des Dépenses</div>
        <div class="text-3xl font-black text-slate-800 tracking-tighter mb-4">{{ number_format($totalExpenses, 0, '.', ',') }} F</div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $percentageExpenses <= 0 ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' }}">
            {{ $percentageExpenses >= 0 ? '↑' : '↓' }} {{ abs(number_format($percentageExpenses, 1)) }}% vs mois dernier
        </div>
    </div>

    <!-- Résultat Net -->
    <div class="bg-primary p-6 sm:p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(37,99,235,0.2)] text-white relative overflow-hidden group">
        <div class="relative z-10 h-full flex flex-col justify-between">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center mb-8">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-2">Résultat Net</div>
                <div class="text-4xl font-black tracking-tighter">{{ number_format($profit, 0, '.', ',') }} F</div>
            </div>
            <div class="mt-8 text-[10px] font-black uppercase tracking-widest opacity-40">Valuation en temps réel</div>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
    </div>
</div>

<!-- Graphiques -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
    <!-- Tendances -->
    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 sm:p-10 shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-center mb-10">
            <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight">Tendances Flux</h3>
            <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 cursor-pointer hover:bg-slate-100 transition-all">6 Derniers Mois ▾</div>
        </div>
        <div class="relative h-64 mt-8 flex items-end justify-between gap-1">
            <svg preserveAspectRatio="none" viewBox="0 0 1200 200" class="absolute left-0 bottom-0 w-full h-full">
                <path d="M0,150 C200,100 400,180 600,80 C800,10 1000,120 1200,50 L1200,200 L0,200 Z" fill="rgba(37, 99, 235, 0.05)" />
                <path d="M0,150 C200,100 400,180 600,80 C800,10 1000,120 1200,50" stroke="#2563eb" stroke-width="4" fill="none" stroke-linecap="round" />
            </svg>
            <div class="absolute -bottom-10 left-0 right-0 flex justify-between px-2">
                @foreach($monthlyTrends as $trend)
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ strtoupper($trend['label']) }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Répartition -->
    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-6 sm:p-10 shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-center mb-10">
            <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight">Répartition Frais</h3>
            <div class="px-3 py-1 bg-slate-100/50 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ now()->format('F Y') }}</div>
        </div>
        <div class="space-y-8">
            @forelse($categoryBreakdown as $breakdown)
            @php $perc = $totalExpenses > 0 ? ($breakdown['total'] / $totalExpenses) * 100 : 0; @endphp
            <div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-700">{{ $breakdown['nom'] }}</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ number_format($breakdown['total'], 0, '.', ',') }} F</span>
                </div>
                <div class="h-2 w-full bg-slate-50 rounded-full border border-slate-100 overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all duration-[1500ms]" style="width: {{ min($perc, 100) }}%;"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-[11px] font-black uppercase tracking-[0.2em] text-slate-300 border-2 border-dashed border-slate-100 rounded-3xl">Aucun mouvement ce mois</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Centre de Rapports -->
<div class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight">Génération de Rapports</h3>
        <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-[9px] font-black uppercase tracking-widest">Exports PDF & Excel</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @php
            $reports = [
                [
                    'id' => 'daily', 
                    'title' => 'Journalier', 
                    'desc' => 'Entrées & Sorties du jour', 
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'bg' => 'bg-blue-50', 'text' => 'text-blue-500'
                ],
                [
                    'id' => 'monthly', 
                    'title' => 'Mensuel', 
                    'desc' => 'Total détaillé par mois', 
                    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'bg' => 'bg-primary/10', 'text' => 'text-primary'
                ],
                [
                    'id' => 'annual', 
                    'title' => 'Annuel', 
                    'desc' => 'Statistiques financières', 
                    'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                    'bg' => 'bg-amber-50', 'text' => 'text-amber-500'
                ],
            ];
        @endphp

        @foreach($reports as $rep)
        <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="w-12 h-12 rounded-xl {{ $rep['bg'] }} {{ $rep['text'] }} flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $rep['icon'] }}"></path></svg>
            </div>
            <h4 class="font-black text-[#0f766e] text-sm tracking-tight mb-1">Rapport {{ $rep['title'] }}</h4>
            <p class="text-[10px] font-bold text-slate-400 mb-6 uppercase tracking-widest line-clamp-1">{{ $rep['desc'] }}</p>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <form action="{{ route('reports.export') }}" method="POST" target="_blank" class="flex-1">
                    @csrf
                    <input type="hidden" name="type" value="{{ $rep['id'] }}">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="w-full h-10 bg-red-50 hover:bg-red-500 hover:text-white text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-1.5" title="Télécharger en PDF">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </button>
                </form>
                <form action="{{ route('reports.export') }}" method="POST" target="_blank" class="flex-1">
                    @csrf
                    <input type="hidden" name="type" value="{{ $rep['id'] }}">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" class="w-full h-10 bg-green-50 hover:bg-green-600 hover:text-white text-green-700 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-1.5" title="Télécharger en Excel (CSV)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        EXCEL
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Bilan Mensuel -->
<div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)] mb-12">
    <div class="p-6 sm:p-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/20">
        <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight">Bilan Mensuel Détaillé</h3>
        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest">12 derniers mois</span>
    </div>
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 bg-slate-50/10">
                    <th class="px-6 sm:px-10 py-6 whitespace-nowrap">Mois</th>
                    <th class="py-6 whitespace-nowrap">Entrées</th>
                    <th class="py-6 whitespace-nowrap">Sorties</th>
                    <th class="px-6 sm:px-10 py-6 text-right whitespace-nowrap">Solde Net</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($bilanAnnuel as $bilan)
                <tr class="hover:bg-slate-50/50 transition-all group">
                    <td class="px-6 sm:px-10 py-6 font-black text-slate-800 uppercase tracking-tight text-xs whitespace-nowrap">{{ $bilan['mois'] }}</td>
                    <td class="py-6 font-bold text-teal-600 text-sm tracking-tighter whitespace-nowrap">{{ number_format($bilan['entrees'], 0, '.', ',') }} F</td>
                    <td class="py-6 font-bold text-red-500 text-sm tracking-tighter whitespace-nowrap">{{ number_format($bilan['sorties'], 0, '.', ',') }} F</td>
                    <td class="px-6 sm:px-10 py-6 text-right font-black tracking-tighter text-sm {{ $bilan['solde'] >= 0 ? 'text-primary' : 'text-red-600' }} whitespace-nowrap">
                        {{ $bilan['solde'] >= 0 ? '+' : '' }}{{ number_format($bilan['solde'], 0, '.', ',') }} F
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Tableau -->
<div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)]">
    <div class="p-6 sm:p-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/20">
        <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight">Registre des opérations récentes</h3>
    </div>

    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 bg-slate-50/10">
                    <th class="px-6 sm:px-10 py-6 whitespace-nowrap">Opération</th>
                    <th class="hidden md:table-cell py-6 whitespace-nowrap">Catégorie</th>
                    <th class="py-6 whitespace-nowrap">État</th>
                    <th class="py-6 whitespace-nowrap">Date</th>
                    <th class="px-6 sm:px-10 py-6 text-right whitespace-nowrap">Volume</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($transactions as $t)
                <tr class="hover:bg-slate-50/50 transition-all group">
                    <td class="px-6 sm:px-10 py-6 whitespace-nowrap">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-primary/5 transition-colors shrink-0">
                                {{ $t->type == 'entrée' ? '💵' : '📦' }}
                            </div>
                            <div class="font-black text-slate-800 uppercase tracking-tight text-xs">{{ $t->libelle }}</div>
                        </div>
                    </td>
                    <td class="hidden md:table-cell py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">{{ $t->categorie ? $t->categorie->nom : 'N/A' }}</td>
                    <td class="py-6 whitespace-nowrap">
                        @php
                            $statutClass = match($t->statut ?? 'approuvé') {
                                'approuvé'   => 'bg-green-50 text-green-600 border-green-100',
                                'en_attente' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'rejeté'     => 'bg-red-50 text-red-600 border-red-100',
                                default      => 'bg-blue-50 text-blue-600 border-blue-100',
                            };
                            $statutLabel = match($t->statut ?? 'approuvé') {
                                'approuvé'   => 'Approuvé',
                                'en_attente' => 'En attente',
                                'rejeté'     => 'Rejeté',
                                default      => 'Confirmé',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statutClass }}">{{ $statutLabel }}</span>
                    </td>
                    <td class="py-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                    <td class="px-6 sm:px-10 py-6 text-right font-black tracking-tighter text-sm {{ $t->type == 'entrée' ? 'text-primary' : 'text-slate-800' }} whitespace-nowrap">
                        {{ $t->type == 'entrée' ? '+' : '-' }}{{ number_format($t->montant, 0, '.', ',') }} F
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div class="px-6 sm:px-10 py-6 border-t border-slate-50 bg-slate-50/10">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
