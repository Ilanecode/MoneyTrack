@extends('layouts.app')

@section('title', "Journal d'Audit")
@section('breadcrumb', 'Sécurité & Traçabilité')

@section('content')
<div class="flex justify-between items-center mb-12">
    <div>
        <h1 class="text-4xl font-black text-[#0f766e] tracking-tighter mb-1">Journal d'Audit</h1>
        <p class="text-slate-400 text-sm font-semibold">Historique complet des actions effectuées sur le système.</p>
    </div>
    <span class="px-4 py-2 bg-primary/10 text-primary rounded-xl text-[10px] font-black uppercase tracking-widest">{{ $audits->total() }} Événements</span>
</div>

<div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="p-10 border-b border-slate-50 flex flex-col xl:flex-row xl:justify-between xl:items-center gap-6 bg-slate-50/20">
        <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight">Registre de traçabilité</h3>
        
        <form method="GET" action="{{ route('audits.index') }}" class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-4 w-full xl:w-auto">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label for="date" class="text-xs font-bold text-slate-500 uppercase tracking-wider w-12 sm:w-auto">Date</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}" class="flex-1 sm:flex-none rounded-xl border-slate-200 bg-white text-sm font-semibold text-slate-700 focus:ring-[#0f766e] focus:border-[#0f766e] px-4 py-2">
            </div>
            
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label for="time" class="text-xs font-bold text-slate-500 uppercase tracking-wider w-12 sm:w-auto">Heure</label>
                <input type="time" name="time" id="time" value="{{ request('time') }}" class="flex-1 sm:flex-none rounded-xl border-slate-200 bg-white text-sm font-semibold text-slate-700 focus:ring-[#0f766e] focus:border-[#0f766e] px-4 py-2">
            </div>
            
            <button type="submit" class="w-full sm:w-auto bg-[#0f766e] hover:bg-[#0f766e]/90 text-white px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-[#0f766e]/20">
                Filtrer
            </button>
            @if(request()->hasAny(['date', 'time']) && (request('date') != '' || request('time') != ''))
                <a href="{{ route('audits.index') }}" class="w-full sm:w-auto text-center bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 bg-slate-50/10">
                    <th class="px-10 py-6 whitespace-nowrap">Chronologie</th>
                    <th class="py-6 whitespace-nowrap">Opérateur</th>
                    <th class="py-6 whitespace-nowrap">Action Système</th>
                    <th class="hidden md:table-cell py-6 whitespace-nowrap">Description Détaillée</th>
                    <th class="hidden lg:table-cell px-10 py-6 text-right whitespace-nowrap">Adresse Réseau</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($audits as $audit)
                <tr class="hover:bg-slate-50/50 transition-all group">
                    <td class="px-10 py-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">
                        {{ $audit->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="py-6 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary/5 flex items-center justify-center text-primary font-black text-[10px] border border-primary/10">
                                {{ strtoupper(substr($audit->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="font-black text-slate-800 uppercase tracking-tight text-[11px]">{{ $audit->user->name ?? 'Système' }}</div>
                        </div>
                    </td>
                    <td class="py-6 whitespace-nowrap">
                        @php
                            $actionClass = match($audit->action) {
                                'création', 'connexion' => 'bg-green-50 text-green-600 border-green-100',
                                'suppression', 'déconnexion' => 'bg-red-50 text-red-600 border-red-100',
                                default => 'bg-amber-50 text-amber-600 border-amber-100',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $actionClass }}">
                            {{ $audit->action }}
                        </span>
                    </td>
                    <td class="hidden md:table-cell py-6 text-[11px] font-bold text-slate-600 tracking-tight whitespace-nowrap">{{ $audit->description }}</td>
                    <td class="hidden lg:table-cell px-10 py-6 text-right whitespace-nowrap">
                        <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-mono text-slate-400 group-hover:bg-white transition-colors">{{ $audit->ip_address }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-32 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-300">Aucune archive disponible</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/10">
        {{ $audits->links() }}
    </div>
</div>
@endsection
