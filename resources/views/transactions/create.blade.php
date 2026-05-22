@extends('layouts.app')

@section('title', 'Ajouter une Entrée')

@section('content')
<div class="max-w-2xl mx-auto pt-6 pb-12">
    {{-- Header local --}}
    <div class="text-center mb-10">
        <h1 class="text-[2rem] font-extrabold text-[#0f766e] tracking-tight">Ajouter une Entrée</h1>
        <p class="text-[0.95rem] font-medium text-slate-600 mt-2">Enregistrez vos nouveaux revenus avec précision.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-[24px] p-10 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-100">
        <form action="{{ route('transactions.store') }}" method="POST" id="entree-form">
            @csrf
            <input type="hidden" name="type" value="entrée">
            <input type="hidden" name="mode_paiement" id="input-mode" value="Cash">
            <input type="hidden" name="categorie_id" id="input-cat" value="{{ $categories->first()->id ?? '' }}">

            {{-- Montant + Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-6">
                <div>
                    <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-2">Montant</label>
                    <div class="relative">
                        <input type="number" name="montant" class="w-full bg-[#f1f5f9] border-none rounded-xl py-3.5 pl-4 pr-10 text-[0.95rem] font-bold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="0.00" step="0.01" min="0" required autofocus value="{{ old('montant') }}">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-600 text-[1rem]">F</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-2">Date</label>
                    <input type="date" name="date" class="w-full bg-[#f1f5f9] border-none rounded-xl px-4 py-3.5 text-[0.95rem] font-bold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
            </div>

            {{-- Intitulé --}}
            <div class="mb-6">
                <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-2">Intitulé / Description</label>
                <input type="text" name="libelle" class="w-full bg-[#f1f5f9] border-none rounded-xl px-4 py-3.5 text-[0.95rem] font-bold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="ex: Paiement client XYZ, Vente #123..." value="{{ old('libelle') }}" required>
            </div>

            {{-- Catégorie (Source) --}}
            <div class="mb-8">
                <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-3">Source de l'entrée</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($categories as $index => $cat)
                    <button type="button" class="cat-btn flex flex-col items-center justify-center gap-3 py-5 rounded-[16px] transition-all font-extrabold text-[0.75rem] border-2 {{ $index === 0 ? 'selected border-[#0d9488] bg-[#0d9488] text-white shadow-md shadow-teal-500/25' : 'border-transparent bg-[#f1f5f9] text-[#475569] hover:bg-[#e2e8f0]' }}"
                        data-cat-id="{{ $cat->id }}"
                        onclick="selectCat(this)">
                        
                        @php $nom = Str::lower($cat->nom); @endphp
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                           @if(Str::contains($nom, 'vente')) <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                           @elseif(Str::contains($nom, 'client') || Str::contains($nom, 'paiement')) <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                           @elseif(Str::contains($nom, 'salaire') || Str::contains($nom, 'subvention') || Str::contains($nom, 'contribution')) <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                           @else <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                           @endif
                        </svg>
                        <span class="text-center px-2">{{ $cat->nom }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Mode de paiement --}}
            <div class="mb-6">
                <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-2">Mode de paiement</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <button type="button" class="payment-btn flex flex-col items-center justify-center gap-2 py-4 rounded-xl border-2 transition-all font-bold text-[0.8rem] selected border-teal-600 bg-[#f0fdfa] text-teal-700" data-mode="Cash" onclick="selectMode(this)">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        Cash
                    </button>
                    <button type="button" class="payment-btn flex flex-col items-center justify-center gap-2 py-4 rounded-xl border-2 transition-all font-bold text-[0.8rem] border-transparent bg-[#f8fafc] text-slate-600 hover:bg-[#f1f5f9]" data-mode="Mobile Money" onclick="selectMode(this)">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/></svg>
                        Mobile Money
                    </button>
                    <button type="button" class="payment-btn flex flex-col items-center justify-center gap-2 py-4 rounded-xl border-2 transition-all font-bold text-[0.8rem] border-transparent bg-[#f8fafc] text-slate-600 hover:bg-[#f1f5f9]" data-mode="Chèque" onclick="selectMode(this)">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10h3v7H4zM10.5 10h3v7h-3zM2 19h20v3H2zM17 10h3v7h-3zM12 1L2 6v2h20V6z"/></svg>
                        Chèque
                    </button>
                </div>
            </div>

            {{-- Projet Associé (Paiement Fractionné) --}}
            <div class="mb-6">
                <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-2">Projet associé (Paiement d'un reste à payer)</label>
                <select name="project_id" class="w-full bg-[#f1f5f9] border-none rounded-xl px-4 py-3.5 text-[0.95rem] font-bold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all appearance-none pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 9l-7 7-7-7\'></path></svg>'); background-repeat: no-repeat; background-position: right 14px center; background-size: 16px;">
                    <option value="">-- Aucun projet (Entrée standard) --</option>
                    @if(isset($projects))
                        @foreach($projects as $projet)
                            <option value="{{ $projet->id }}" {{ old('project_id') == $projet->id ? 'selected' : '' }}>
                                {{ $projet->client_name }} (Reste : {{ number_format($projet->reste_a_payer, 0, ',', ' ') }} F)
                            </option>
                        @endforeach
                    @endif
                </select>
                <p class="text-[0.7rem] font-medium text-slate-500 mt-2">Optionnel : À remplir uniquement si cette entrée est le paiement d'un devis/projet existant.</p>
            </div>

            {{-- Commentaire --}}
            <div class="mb-8">
                <label class="block text-[0.65rem] font-extrabold tracking-[0.1em] uppercase text-[#0f766e] mb-2">Commentaire (Optionnel)</label>
                <textarea name="description" class="w-full bg-[#f1f5f9] border-none rounded-xl px-4 py-3.5 text-[0.95rem] font-bold text-slate-900 focus:ring-4 focus:ring-teal-500/20 transition-all placeholder:text-slate-400 placeholder:font-medium resize-none" rows="4" placeholder="Notes additionnelles sur cette entrée...">{{ old('description') }}</textarea>
            </div>

            {{-- Boutons --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button type="submit" class="w-full py-4 bg-[#0d9488] text-white rounded-xl text-[0.95rem] font-bold shadow-md shadow-teal-500/20 transition-all hover:bg-[#0f766e] hover:-translate-y-px text-center">
                    Enregistrer
                </button>
                <a href="{{ route('dashboard') }}" class="w-full py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-xl text-[0.95rem] font-bold text-center transition-all hover:border-[#0d9488] hover:text-[#0d9488] flex items-center justify-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    {{-- Sécurité --}}
    <div class="mt-8 flex items-center justify-center gap-2 text-[0.65rem] font-extrabold tracking-[0.15em] uppercase text-slate-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        Transaction sécurisée par MoneyTrack
    </div>
</div>

<script>
function selectMode(el) {
    const defaultClasses = ["border-transparent", "bg-[#f8fafc]", "text-slate-600", "hover:bg-[#f1f5f9]"];
    const activeClasses = ["border-teal-600", "bg-[#f0fdfa]", "text-teal-700", "selected"];

    document.querySelectorAll('.payment-btn').forEach(b => {
        b.classList.remove(...activeClasses);
        b.classList.add(...defaultClasses);
    });

    el.classList.remove(...defaultClasses);
    el.classList.add(...activeClasses);
    
    document.getElementById('input-mode').value = el.dataset.mode;
}

function selectCat(el) {
    const defaultClasses = ["border-transparent", "bg-[#f1f5f9]", "text-[#475569]", "hover:bg-[#e2e8f0]"];
    const activeClasses = ["border-[#0d9488]", "bg-[#0d9488]", "text-white", "shadow-md", "shadow-teal-500/25", "selected"];

    document.querySelectorAll('.cat-btn').forEach(b => {
        b.classList.remove(...activeClasses);
        b.classList.add(...defaultClasses);
    });

    el.classList.remove(...defaultClasses);
    el.classList.add(...activeClasses);
    
    document.getElementById('input-cat').value = el.dataset.catId;
}
</script>
@endsection
