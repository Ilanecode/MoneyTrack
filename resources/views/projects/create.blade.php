@extends('layouts.app')

@section('title', 'Nouveau Projet')
@section('page_title', 'Ouvrir un Projet')
@section('page_subtitle', 'Enregistrer les informations d\'un nouveau client et son projet')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8 max-w-4xl mx-auto">
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Section 1 : Informations Client -->
        <div>
            <h3 class="text-lg font-bold text-[#0f766e] mb-4 pb-2 border-b border-slate-100">1. Informations du Client</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Nom & Prénom <span class="text-red-500">*</span></label>
                    <input type="text" name="client_name" value="{{ old('client_name') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] transition-all placeholder:text-slate-400" placeholder="Ex: Jean Dupont">
                    @error('client_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Contact</label>
                    <input type="text" name="client_contact" value="{{ old('client_contact') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] transition-all placeholder:text-slate-400" placeholder="Ex: 01 02 03 04 05">
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
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] transition-all placeholder:text-slate-400" placeholder="Détails sur le service demandé par le client...">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Budget Total (F CFA) <span class="text-red-500">*</span></label>
                    <input type="number" name="budget" id="budget" value="{{ old('budget') }}" required min="0" step="1" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] font-bold transition-all placeholder:text-slate-400" placeholder="0">
                    @error('budget') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 3 : Règlement -->
        <div>
            <h3 class="text-lg font-bold text-[#0f766e] mb-4 pb-2 border-b border-slate-100">3. Phase de Règlement</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-[0.8rem] font-bold text-slate-700 mb-3">Modalité de paiement <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors flex-1 bg-white">
                            <input type="radio" name="modalite_paiement" value="cash" id="radioCash" class="w-4 h-4 text-[#0f766e] focus:ring-[#0f766e]" {{ old('modalite_paiement', 'cash') == 'cash' ? 'checked' : '' }}>
                            <span class="ml-2 font-bold text-slate-700 text-sm">Paiement Cash (Totalité)</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors flex-1 bg-white">
                            <input type="radio" name="modalite_paiement" value="fraction" id="radioFraction" class="w-4 h-4 text-[#0f766e] focus:ring-[#0f766e]" {{ old('modalite_paiement') == 'fraction' ? 'checked' : '' }}>
                            <span class="ml-2 font-bold text-slate-700 text-sm">Paiement en Fraction</span>
                        </label>
                    </div>
                </div>

                <div id="divAvance" class="hidden space-y-6">
                    <div>
                        <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Montant de l'avance (F CFA)</label>
                        <input type="number" name="montant_avance" id="montant_avance" value="{{ old('montant_avance') }}" min="0" step="1" class="w-full bg-orange-50 border border-orange-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent px-4 py-2.5 text-[0.9rem] font-bold transition-all placeholder:text-slate-400" placeholder="Montant payé aujourd'hui">
                        <p class="text-xs text-slate-500 mt-2">Ce montant sera enregistré comme une entrée en caisse aujourd'hui. Laissez vide si le client ne paie rien pour le moment.</p>
                        @error('montant_avance') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[0.8rem] font-bold text-slate-700 mb-2">Date limite pour le solde <span class="text-red-500">*</span></label>
                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-[#0f766e] focus:border-transparent px-4 py-2.5 text-[0.9rem] font-bold transition-all">
                        <p class="text-xs text-slate-500 mt-2">Date à laquelle le client doit avoir terminé de payer. En cas de dépassement, une alerte de retard sera affichée.</p>
                        @error('deadline') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('projects.index') }}" class="px-5 py-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 font-bold rounded-xl text-[0.85rem] transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#0f766e] hover:bg-[#0d9488] text-white font-bold rounded-xl shadow-sm text-[0.85rem] transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                Ouvrir le projet
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioCash = document.getElementById('radioCash');
        const radioFraction = document.getElementById('radioFraction');
        const divAvance = document.getElementById('divAvance');
        const budgetInput = document.getElementById('budget');
        const avanceInput = document.getElementById('montant_avance');

        function toggleAvance() {
            if (radioFraction.checked) {
                divAvance.classList.remove('hidden');
                // Optionnel: On peut vider l'avance si on passe en fraction
                // avanceInput.value = '';
            } else {
                divAvance.classList.add('hidden');
                // Si cash, le montant payé c'est le budget (géré dans le controller)
            }
        }

        radioCash.addEventListener('change', toggleAvance);
        radioFraction.addEventListener('change', toggleAvance);
        
        // Initialisation au chargement
        toggleAvance();
    });
</script>
@endsection
