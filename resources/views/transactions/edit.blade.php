@extends('layouts.app')

@section('title', 'Modifier Transaction')
@section('breadcrumb', 'Modifier une Opération')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-black text-[#0f766e] tracking-tighter mb-1">Modifier Transaction {{ $transaction->id }}</h1>
    <p class="text-slate-400 text-sm font-semibold">Mettez à jour les informations de cette opération.</p>
</div>

<form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
    @csrf @method('PUT')
    <input type="hidden" name="type" id="input-type" value="{{ $transaction->type }}">
    <input type="hidden" name="categorie_id" id="input-categorie" value="{{ $transaction->categorie_id }}">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Panneau Gauche -->
        <div>
            <!-- Montant -->
            <div class="bg-white border border-primary/20 p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(37,99,235,0.08)] mb-10">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Valeur de l'Opération</label>
                <div class="flex items-baseline gap-4">
                    <span class="text-5xl font-black text-primary/30 tracking-tighter">F</span>
                    <input type="number" name="montant" class="w-full bg-transparent border-none text-6xl font-black text-primary outline-none tracking-tighter" placeholder="0.00" step="0.01" value="{{ old('montant', $transaction->montant) }}" required autofocus>
                </div>
                <div class="h-1.5 w-full bg-slate-100 rounded-full mt-8 overflow-hidden">
                    <div class="h-full w-2/3 bg-primary rounded-full"></div>
                </div>
            </div>

            <!-- Date & Mode -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Date d'Effet</label>
                    <input type="date" name="date" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all" value="{{ old('date', \Carbon\Carbon::parse($transaction->date)->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Mode de Règlement</label>
                    <select name="mode_paiement" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all appearance-none">
                        <option value="Cash" {{ old('mode_paiement', $transaction->mode_paiement) == 'Cash' ? 'selected' : '' }}>💵 Cash / Espèces</option>
                        <option value="Chèque" {{ old('mode_paiement', $transaction->mode_paiement) == 'Chèque' ? 'selected' : '' }}>🏛️ Chèque Bancaire</option>
                        <option value="Carte" {{ old('mode_paiement', $transaction->mode_paiement) == 'Carte' ? 'selected' : '' }}>💳 Carte Bancaire</option>
                    </select>
                </div>
            </div>

            <!-- Catégories -->
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Sélectionner une Catégorie</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($categories as $cat)
                    @php $isSelected = old('categorie_id', $transaction->categorie_id) == $cat->id; @endphp
                    <div class="cat-card-item cursor-pointer group flex flex-col items-center justify-center gap-3 p-6 rounded-2xl border-2 transition-all {{ $isSelected ? 'bg-primary/5 border-primary/40 shadow-xl ring-4 ring-primary/10' : 'bg-slate-50/50 border-slate-50 hover:bg-white hover:border-primary/20 hover:shadow-xl' }}" data-cat-id="{{ $cat->id }}" data-type="{{ $cat->type }}" onclick="selectCategory(this)">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-2xl">
                            @if(Str::contains(Str::lower($cat->nom), 'carburant')) ⛽ @elseif(Str::contains(Str::lower($cat->nom), 'salaire')) 💰 @elseif(Str::contains(Str::lower($cat->nom), 'loyer')) 🏠 @else 📦 @endif
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-widest {{ $isSelected ? 'text-primary' : 'text-slate-500 group-hover:text-primary' }} transition-colors">{{ $cat->nom }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Type de flux -->
                <div class="mt-8 p-6 bg-slate-50 border border-slate-100 rounded-3xl">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Type de Flux Financier</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="display_type" value="entrée" onchange="document.getElementById('input-type').value = this.value" {{ old('type', $transaction->type) == 'entrée' ? 'checked' : '' }}>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-600">Entrée (+F)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="display_type" value="sortie" onchange="document.getElementById('input-type').value = this.value" {{ old('type', $transaction->type) == 'sortie' ? 'checked' : '' }}>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-600">Sortie (-F)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panneau Droite -->
        <div class="h-48px bg-slate-50 p-5 rounded-2xl border border-slate-100">
            <h3 class="font-black text-xl text-[#0f766e] uppercase tracking-tight mb-8">Détails descriptifs</h3>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Intitulé de la Transaction</label>
                <input type="text" name="libelle" value="{{ old('libelle', $transaction->libelle) }}" class="w-full bg-white border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-sm" placeholder="ex: Course hebdomadaire">
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Destinataire / Provenance</label>
                <div class="relative">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300">👤</span>
                    <input type="text" name="beneficiaire" value="{{ old('beneficiaire', $transaction->type == 'entrée' ? $transaction->source : $transaction->beneficiaire) }}" class="w-full bg-white border border-slate-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-sm" placeholder="Nom de la personne ou entreprise">
                </div>
            </div>

            <div class="mb-10">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Notes et Commentaires (Optionnel)</label>
                <textarea name="description" class="w-full bg-white border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-sm" rows="5" placeholder="Précisez ici les détails importants...">{{ old('description', $transaction->description) }}</textarea>
            </div>

            <div class="flex flex-col gap-4">
                <button type="submit" class="h-16 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-1 transition-all">Mettre à Jour l'Opération</button>
                <a href="{{ route('transactions.index') }}" class="h-16 flex items-center justify-center rounded-2xl bg-white text-slate-400 font-black text-xs uppercase tracking-widest border border-slate-200 hover:bg-slate-50 transition-all" style="text-decoration:none;">Annuler les Changements</a>
            </div>
        </div>
    </div>
</form>

<script>
    function selectCategory(element) {
        document.querySelectorAll('.cat-card-item').forEach(c => {
            c.classList.remove('bg-primary/5', 'border-primary/40', 'shadow-xl', 'ring-4', 'ring-primary/10');
            c.classList.add('bg-slate-50/50', 'border-slate-50');
            c.querySelector('div:last-child').classList.remove('text-primary');
            c.querySelector('div:last-child').classList.add('text-slate-500');
        });
        element.classList.remove('bg-slate-50/50', 'border-slate-50');
        element.classList.add('bg-primary/5', 'border-primary/40', 'shadow-xl', 'ring-4', 'ring-primary/10');
        element.querySelector('div:last-child').classList.remove('text-slate-500');
        element.querySelector('div:last-child').classList.add('text-primary');
        document.getElementById('input-categorie').value = element.dataset.catId;
        document.getElementById('input-type').value = element.dataset.type;
    }
</script>
@endsection
