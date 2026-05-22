@extends('layouts.app')

@section('title', 'Modifier le Projet')
@section('page_title', 'Modifier un Projet')
@section('page_subtitle', 'Mettre à jour les informations du client et de son projet')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8 max-w-4xl mx-auto">
    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Section 1 : Informations Client -->
        <div>
            <h3 class="text-lg font-bold text-[#0f766e] mb-4 pb-2 border-b border-slate-100">1. Informations du Client</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Nom & Prénom <span class="text-red-500">*</span></label>
                    <input type="text" name="client_name" value="{{ old('client_name', $project->client_name) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] transition-all placeholder:text-slate-400" placeholder="Ex: Jean Dupont">
                    @error('client_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Contact</label>
                    <input type="text" name="client_contact" value="{{ old('client_contact', $project->client_contact) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] transition-all placeholder:text-slate-400" placeholder="Ex: 01 02 03 04 05">
                    @error('client_contact') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 2 : Détails du Service -->
        <div>
            <h3 class="text-lg font-bold text-[#0f766e] mb-4 pb-2 border-b border-slate-100">2. Détails du Service</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Description du Service</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] transition-all placeholder:text-slate-400" placeholder="Détails sur le service demandé par le client...">{{ old('description', $project->description) }}</textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Budget Total (F CFA) <span class="text-red-500">*</span></label>
                    <input type="number" name="budget" id="budget" value="{{ old('budget', $project->budget) }}" required min="0" step="1" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] font-bold transition-all placeholder:text-slate-400" placeholder="0">
                    <p class="text-xs text-slate-500 mt-2">Le changement de budget recalculera automatiquement le reste à payer en fonction des transactions existantes.</p>
                    @error('budget') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Date limite pour le solde</label>
                    <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('Y-m-d') : '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] font-bold transition-all">
                    @error('deadline') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 font-bold rounded-xl text-[0.85rem] transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#0f766e] hover:bg-[#0d9488] text-white font-bold rounded-xl shadow-sm text-[0.85rem] transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
