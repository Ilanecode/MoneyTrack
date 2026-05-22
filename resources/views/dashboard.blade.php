@extends('layouts.app')

@section('title', 'Tableau de Bord')
@section('page_title', 'Aperçu Financier')
@section('page_subtitle', 'Analyse en temps réel de votre patrimoine.')

@section('content')
<div class="flex gap-6 mb-6 flex-col lg:flex-row">

    {{-- ── Balance Card (left 65%) ── --}}
    <div class="flex-[1.8] min-w-0">
        <div class="bg-white rounded-[24px] p-8 relative overflow-hidden border border-slate-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] h-full flex flex-col justify-between">
            
            {{-- Abstract Shapes in Background --}}
            <div class="absolute -right-[15%] -top-[10%] w-[50%] h-[150%] bg-[#f0fdfa]/60 rounded-[40%_60%_70%_30%/40%_50%_60%_50%] transform rotate-12 pointer-events-none"></div>
            <div class="absolute right-[5%] bottom-[10%] w-[25%] h-[60%] bg-[#ccfbf1]/40 rounded-full blur-[40px] pointer-events-none"></div>

            <div>
                <div class="text-[0.68rem] font-bold tracking-[0.15em] uppercase text-[#0d9488] mb-3 flex items-center gap-2 relative z-10">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M21 7.5V6a2 2 0 00-2-2H5a2 2 0 00-2 2v1h18zM3 9.5v8a2 2 0 002 2h14a2 2 0 002-2v-8H3zm4 4.5h3v-2H7v2z"/></svg>
                    Solde Actuel
                </div>
                
                <div class="text-[2rem] md:text-[2.8rem] font-extrabold text-[#0f172a] tracking-tight leading-none flex flex-wrap items-center gap-2 md:gap-4 relative z-10">
                    <span>{{ number_format($solde, 2, ',', ' ') }} F</span>
                    <span class="inline-flex items-center gap-1 text-[0.7rem] font-bold {{ $growth >= 0 ? 'bg-[#ccfbf1] text-[#0f766e]' : 'bg-red-50 text-red-600' }} px-2.5 py-1 rounded-full whitespace-nowrap tracking-wide">
                        <svg class="w-3 h-3 {{ $growth < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                        {{ number_format(abs($growth), 1, ',', ' ') }}%
                    </span>
                </div>
            </div>
            
            @if(!auth()->user() || !auth()->user()->isAdmin())
            <div class="flex flex-wrap gap-3 mt-8 md:mt-12 relative z-10">
                <a href="{{ route('transactions.create') }}" class="flex-auto justify-center inline-flex items-center gap-2 px-4 md:px-6 py-3 bg-[#0d9488] text-white rounded-xl text-[0.85rem] font-bold transition-all hover:bg-[#0f766e] focus:ring-4 focus:ring-teal-500/20 shadow-sm shadow-teal-500/30">
                    <svg class="w-4 h-4 rounded-full border border-white/40 p-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2h6z"/></svg>
                    <span class="whitespace-nowrap">Ajouter fonds</span>
                </a>
                <a href="{{ route('transactions.create.sortie') }}" class="flex-auto justify-center inline-flex items-center gap-2 px-4 md:px-6 py-3 bg-red-500 text-white rounded-xl text-[0.85rem] font-bold transition-all hover:bg-red-600 focus:ring-4 focus:ring-red-500/20 shadow-sm shadow-red-500/30">
                    <svg class="w-4 h-4 rounded-full border border-white/40 p-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M13 11V5h-2v6H5v2h6v6h2v-6h6v-2h-6z"/></svg>
                    <span class="whitespace-nowrap">Dépense</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="flex-auto justify-center inline-flex items-center gap-2 px-4 md:px-6 py-3 bg-[#f1f5f9] text-[#0f766e] rounded-xl text-[0.85rem] font-bold transition-all hover:bg-[#e2e8f0]">
                    Transférer
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- ── KPI Cards (right 35%) ── --}}
    <div class="flex-1 min-w-0 flex flex-col gap-6">
        {{-- Income --}}
        <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] flex items-center justify-between flex-1">
            <div>
                <div class="text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-slate-500 mb-2">Total des revenus aujourd'hui</div>
                <div class="text-[1.7rem] font-extrabold text-[#0f172a] tracking-tight">{{ number_format($entreesJour, 2, ',', ' ') }} F</div>
            </div>
            <div class="w-12 h-12 rounded-[14px] flex items-center justify-center bg-[#ccfbf1] text-[#0d9488] shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
            </div>
        </div>
        {{-- Expenses --}}
        <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] flex items-center justify-between flex-1">
            <div>
                <div class="text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-slate-500 mb-2">Total des dépenses aujourd'hui</div>
                <div class="text-[1.7rem] font-extrabold text-[#0f172a] tracking-tight">{{ number_format($sortiesJour, 2, ',', ' ') }} F</div>
            </div>
            <div class="w-12 h-12 rounded-[14px] flex items-center justify-center bg-[#fee2e2] text-[#ef4444] shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"/></svg>
            </div>
        </div>
    </div>
</div>

{{-- ── Bottom Row ── --}}
<div class="flex gap-6 flex-col lg:flex-row">

    {{-- Daily Spending History ── --}}
    <div class="flex-[1.8] min-w-0">
        <div class="bg-white rounded-[24px] p-8 border border-slate-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] h-full flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-[1.1rem] font-bold text-[#0f766e]">Historique des dépenses quotidiennes</h2>
                </div>
                <div class="flex gap-1 bg-[#f1f5f9] rounded-lg p-1">
                    <button class="toggle-btn px-4 py-1.5 rounded-md text-[0.7rem] font-bold transition-colors bg-[#0d9488] text-white shadow-sm" id="btnWeek" onclick="switchChart('week')">SEMAINE</button>
                    <button class="toggle-btn px-4 py-1.5 rounded-md text-[0.7rem] font-bold transition-colors bg-transparent text-[#64748b] hover:text-[#0f172a]" id="btnMonth" onclick="switchChart('month')">MOIS</button>
                </div>
            </div>
            
            <div class="flex items-end justify-between gap-2 h-[220px] mt-auto relative" id="barChart">
                @foreach($chartData as $data)
                <div class="flex flex-col items-center flex-1 gap-3 relative group">
                    {{-- Tooltip hover --}}
                    <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-[0.65rem] font-bold px-2.5 py-1 rounded-md mb-2 pointer-events-none whitespace-nowrap">
                        {{ number_format($data['amount'], 0, ',', ' ') }} F
                    </div>
                    {{-- Bar --}}
                    <div class="w-full max-w-[48px] rounded-t-lg transition-all duration-300 min-h-[4px] {{ $data['isToday'] ? 'bg-[#008f8f]' : 'bg-[#e2e8f0] group-hover:bg-[#cbd5e1]' }}" style="height:{{ $data['height'] }}px;"></div>
                    <span class="text-[0.65rem] font-extrabold {{ $data['isToday'] ? 'text-[#008f8f]' : 'text-slate-400' }}">{{ $data['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Transactions ── --}}
    <div class="flex-1 min-w-0 mt-6 xl:mt-0">
        <div class="bg-white rounded-[24px] p-8 border border-slate-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] h-full flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[1.1rem] font-bold text-[#0f766e]">Opérations récentes</h2>
                <a href="{{ route('transactions.index') }}" class="text-[0.75rem] font-bold text-[#0d9488] hover:underline">Voir tout</a>
            </div>

            <div class="flex flex-col gap-1 overflow-y-auto pr-2" style="max-height: 280px;">
                @forelse($dernieresTransactions as $transaction)
                <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0 group">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-medium bg-[#f8fafc] flex items-center justify-center shrink-0 {{ $transaction->type == 'entrée' ? 'text-teal-600' : 'text-slate-600' }} group-hover:bg-slate-100 transition-colors" style="border-radius: 12px;">
                            @if($transaction->type == 'entrée')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="text-[0.88rem] font-bold text-[#0f172a]">{{ $transaction->libelle }}</div>
                            <div class="text-[0.72rem] font-medium text-slate-500 mt-0.5">
                                {{ $transaction->categorie->nom ?? 'Shopping' }} •
                                @if($transaction->date->isToday()) Aujourd'hui
                                @elseif($transaction->date->isYesterday()) Hier
                                @elseif($transaction->date->diffInDays() < 7) il y a {{ $transaction->date->diffInDays() }} jours
                                @else {{ $transaction->created_at->format('d/m/Y') }}
                                @endif
                                à {{ $transaction->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="text-[0.88rem] font-bold tracking-tight whitespace-nowrap {{ $transaction->type == 'entrée' ? 'text-[#0d9488]' : 'text-[#dc2626]' }}">
                        {{ $transaction->type == 'entrée' ? '+' : '-' }}{{ number_format($transaction->montant, 2, ',', ' ') }} F
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-slate-400 text-[0.85rem] font-bold">
                    Aucune opération récente.
                </div>
                @endforelse
        </div>
    </div>
</div>

{{-- Floating Action Button --}}
@if(!auth()->user() || !auth()->user()->isAdmin())
<a href="{{ route('transactions.create') }}" class="fixed bottom-10 right-10 w-14 h-14 bg-[#0d9488] text-white rounded-full flex items-center justify-center shadow-lg shadow-teal-500/40 transition-all z-20 hover:bg-[#0f766e] hover:scale-105 focus:outline-none focus:ring-4 focus:ring-teal-500/30" title="Nouvelle opération">
    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
</a>
@endif

<script>
function switchChart(mode) {
    const btnWeek = document.getElementById('btnWeek');
    const btnMonth = document.getElementById('btnMonth');
    const activeClass = "toggle-btn px-4 py-1.5 rounded-md text-[0.7rem] font-bold transition-colors bg-[#0d9488] text-white shadow-sm";
    const inactiveClass = "toggle-btn px-4 py-1.5 rounded-md text-[0.7rem] font-bold transition-colors bg-transparent text-[#64748b] hover:text-[#0f172a]";
    
    if (mode === 'week') {
        btnWeek.className = activeClass;
        btnMonth.className = inactiveClass;
    } else {
        btnMonth.className = activeClass;
        btnWeek.className = inactiveClass;
    }
}
</script>
@endsection
