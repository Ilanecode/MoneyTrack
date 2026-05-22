@extends('layouts.app')

@section('title', 'Enregistrer une Dépense')

@section('content')
<div class="max-w-[760px] mx-auto pt-4 pb-12">
    <div class="text-center mb-10">
        <h1 class="text-[2rem] font-extrabold text-[#0f766e] tracking-tight">Ajouter une Dépense</h1>
        <p class="text-[0.95rem] font-medium text-slate-600 mt-2">Enregistrez vos nouvelles sortie avec précision.</p>
    </div>
    <div class="bg-white rounded-[24px] p-6 sm:p-[35px_42px] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-100">

        {{-- Heading --}}
        <div class="border-l-[4px] border-[#0d9488] pl-5 mb-10">
            <h2 class="text-[1.4rem] font-extrabold text-[#0f766e] tracking-tight leading-none mb-2">Détails de la dépense</h2>
            <p class="text-[0.95rem] text-slate-600 font-medium">Saisissez les informations relatives à votre nouvel achat.</p>
        </div>

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="sortie">
            <input type="hidden" name="categorie_id" id="input-categorie" value="{{ $categories->first()->id ?? '' }}">

            {{-- Date + Montant --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mb-8 mt-4">
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Date</label>
                    <div class="relative">
                        <input type="date" name="date" class="w-full bg-[#f1f5f9] border-none rounded-xl py-4 pl-12 pr-4 text-[1rem] font-extrabold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all" value="{{ old('date', date('Y-m-d')) }}" required>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Montant</label>
                    <div class="relative">
                        <input type="number" name="montant" class="w-full bg-[#f1f5f9] border-none rounded-xl py-4 pl-4 pr-12 text-[1.1rem] font-black text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all text-right placeholder:text-slate-400 placeholder:font-medium" placeholder="0.00" step="0.01" min="0.01" required autofocus value="{{ old('montant') }}">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 font-black text-slate-500 text-[1.1rem]">F</span>
                    </div>
                </div>
            </div>

            {{-- Motif --}}
            <div class="mb-8">
                <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Motif / Intitulé</label>
                <input type="text" name="libelle" class="w-full bg-[#e2e8f0]/60 border-none rounded-xl px-4 py-4 text-[0.95rem] font-extrabold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="ex: Courses hebdomadaires" value="{{ old('libelle') }}" required>
            </div>

            {{-- Catégorie --}}
            <div class="mb-8">
                <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Catégorie</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($categories as $index => $cat)
                    <button type="button" class="cat-btn flex flex-col items-center justify-center gap-3 py-5 rounded-[16px] transition-all font-extrabold text-[0.75rem] border-2 {{ $index === 0 ? 'selected border-[#0d9488] bg-[#0d9488] text-white shadow-md shadow-teal-500/25' : 'border-transparent bg-[#f1f5f9] text-[#475569] hover:bg-[#e2e8f0]' }}"
                        data-cat-id="{{ $cat->id }}"
                        onclick="selectCat(this)">
                        
                        @php $nom = Str::lower($cat->nom); @endphp
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                           @if(Str::contains($nom, 'carburant') || Str::contains($nom, 'transport')) <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2l2 9h10l2-9h2M7 5h10M12 5v4"/>
                           @elseif(Str::contains($nom, 'fourniture')) <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z"/>
                           @elseif(Str::contains($nom, 'maintenance') || Str::contains($nom, 'réparation')) <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                           @elseif(Str::contains($nom, 'facture') || Str::contains($nom, 'abonnement') || Str::contains($nom, 'internet') || Str::contains($nom, 'eau') || Str::contains($nom, 'électricité')) <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                           @elseif(Str::contains($nom, 'aliment')) <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                           @elseif(Str::contains($nom, 'restau')) <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                           @elseif(Str::contains($nom, 'loyer')) <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"/>
                           @elseif(Str::contains($nom, 'salaire')) <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                           @else <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                           @endif
                        </svg>
                        {{ $cat->nom }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Bénéficiaire + Mode de paiement --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mb-8">
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Bénéficiaire</label>
                    <input type="text" name="beneficiaire" class="w-full bg-[#e2e8f0]/60 border-none rounded-xl px-4 py-4 text-[0.95rem] font-extrabold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="Nom de l'entreprise ou personne" value="{{ old('beneficiaire') }}">
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Mode de paiement</label>
                    <select name="mode_paiement" class="w-full bg-[#e2e8f0]/60 border-none rounded-xl px-4 py-4 text-[0.95rem] font-extrabold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all appearance-none pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 9l-7 7-7-7\'></path></svg>'); background-repeat: no-repeat; background-position: right 14px center; background-size: 16px;" required>
                        <option value="Chèque" {{ old('mode_paiement', 'Chèque') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                        <option value="Cash" {{ old('mode_paiement') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ old('mode_paiement') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Carte" {{ old('mode_paiement') == 'Carte' ? 'selected' : '' }}>Carte Bancaire</option>
                    </select>
                </div>
            </div>

            {{-- Projet (Optionnel) --}}
            <div class="mb-8">
                <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Projet associé (Optionnel)</label>
                <select name="project_id" class="w-full bg-[#e2e8f0]/60 border-none rounded-xl px-4 py-4 text-[0.95rem] font-extrabold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all appearance-none pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 9l-7 7-7-7\'></path></svg>'); background-repeat: no-repeat; background-position: right 14px center; background-size: 16px;">
                    <option value="">-- Aucun projet --</option>
                    @foreach($projects ?? [] as $projet)
                        <option value="{{ $projet->id }}" {{ old('project_id') == $projet->id ? 'selected' : '' }}>
                            {{ $projet->client_name }} - {{ number_format($projet->budget, 0, ',', ' ') }} F
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Commentaire --}}
            <div class="mb-10">
                <label class="block text-[0.8rem] font-bold text-[#0f766e] mb-3">Commentaire (Optionnel)</label>
                <textarea name="description" class="w-full bg-[#e2e8f0]/60 border-none rounded-xl px-4 py-4 text-[0.95rem] font-extrabold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium resize-none" rows="3" placeholder="Ajoutez des détails supplémentaires...">{{ old('description') }}</textarea>
            </div>

            {{-- Bouton Submit plein largeur --}}
            <button type="submit" class="w-full py-4 bg-[#0d9488] text-white rounded-[14px] text-[1.05rem] font-extrabold shadow-lg shadow-teal-500/30 transition-all hover:bg-[#0f766e] hover:-translate-y-px text-center">Enregistrer la dépense</button>
        </form>
    </div>
</div>

<script>
function selectCat(el) {
    const defaultClasses = ["border-transparent", "bg-[#f1f5f9]", "text-[#475569]", "hover:bg-[#e2e8f0]"];
    const activeClasses = ["border-[#0d9488]", "bg-[#0d9488]", "text-white", "shadow-md", "shadow-teal-500/25", "selected"];

    document.querySelectorAll('.cat-btn').forEach(b => {
        b.classList.remove(...activeClasses);
        b.classList.add(...defaultClasses);
    });

    el.classList.remove(...defaultClasses);
    el.classList.add(...activeClasses);
    
    document.getElementById('input-categorie').value = el.dataset.catId;
}
</script>
@endsection
