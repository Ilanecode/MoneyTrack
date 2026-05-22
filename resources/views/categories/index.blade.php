@extends('layouts.app')

@section('title', 'Gestion des Catégories')
@section('page_title', 'Gestion des Catégories')
@section('page_subtitle', 'Personnalisez l\'organisation de vos flux financiers.')

@section('content')
<div class="max-w-[1100px] mx-auto pb-12 pt-4">
    <!-- Header -->
    <div class="flex justify-end gap-4 mb-8 w-full sm:w-auto">
        <button class="w-full sm:w-auto justify-center flex items-center gap-2.5 px-6 py-3.5 bg-[#0d9488] hover:bg-[#0f766e] text-white rounded-[10px] text-[0.95rem] font-bold shadow-sm transition-all" onclick="openCategoryModal()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Ajouter une catégorie
        </button>
    </div>

    <!-- Filters & Stats Row -->
    <div class="flex flex-col md:flex-row justify-between items-stretch gap-6 mb-8">
        <!-- Tab Toggle -->
        <div class="flex flex-1 items-center bg-[#f8fafc] p-2 rounded-[16px] border border-slate-100 min-h-[90px] overflow-x-auto">
            <div class="flex w-full items-center justify-around h-full gap-2 min-w-[300px]">
                <a href="{{ route('categories.index') }}" class="flex-1 h-full flex items-center justify-center gap-2 rounded-[12px] {{ !request('type') ? 'bg-white text-[#0d9488] shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-800 font-bold' }} text-[0.9rem] sm:text-[1rem] transition-all whitespace-nowrap p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Toutes
                </a>
                <a href="{{ route('categories.index', ['type' => 'entrée']) }}" class="flex-1 h-full flex items-center justify-center gap-2 rounded-[12px] {{ request('type') == 'entrée' ? 'bg-white text-[#0d9488] shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-800 font-bold' }} text-[0.9rem] sm:text-[1rem] transition-all whitespace-nowrap p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Entrées
                </a>
                <a href="{{ route('categories.index', ['type' => 'sortie']) }}" class="flex-1 h-full flex items-center justify-center gap-2 rounded-[12px] {{ request('type') == 'sortie' ? 'bg-white text-[#0d9488] shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-800 font-bold' }} text-[0.9rem] sm:text-[1rem] transition-all whitespace-nowrap p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    Sorties
                </a>
            </div>
        </div>

        <!-- Total Stats Card -->
        <div class="bg-[#e2f1f0] rounded-[16px] p-6 flex justify-between items-center w-full md:w-[320px] min-h-[90px]">
            <div>
                <p class="text-[0.65rem] font-black text-[#0f766e] uppercase tracking-widest mb-1.5 opacity-80">Total Catégories</p>
                <div class="text-3xl font-black text-[#0f766e] tracking-tight">{{ $toutes->count() ?? 24 }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#ccfbf1] flex items-center justify-center text-[#0f766e]">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 14h4v6H4v-6zm5-5h4v11H9V9zm5-4h4v15h-4V5z"/></svg> 
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[16px] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden mb-8">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-slate-100">
                        <th class="px-8 py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest w-24 whitespace-nowrap">Icône</th>
                        <th class="py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest whitespace-nowrap">Nom de la Catégorie</th>
                        <th class="hidden md:table-cell py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest whitespace-nowrap">Description</th>
                        <th class="py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest w-36 whitespace-nowrap">Type</th>
                        <th class="px-8 py-5 text-right text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest w-32 whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                    $categories = request('type') == 'entrée' ? $entrees : (request('type') == 'sortie' ? $sorties : $toutes);
                    // Generate mock descriptions for display because database doesn't have it natively
                    function getDesc($nom) {
                        $n = strtolower($nom);
                        if (str_contains($n, 'salaire')) return "Revenus principaux de l'emploi";
                        if (str_contains($n, 'vente')) return "Revenus issus des ventes";
                        if (str_contains($n, 'contribution') || str_contains($n, 'don')) return "Apports, cotisations et dons";
                        if (str_contains($n, 'remboursement')) return "Retours de fonds et remboursements";
                        if (str_contains($n, 'virement')) return "Transferts entre comptes";
                        if (str_contains($n, 'fourniture')) return "Matériel, papeterie et équipements";
                        if (str_contains($n, 'maintenance') || str_contains($n, 'réparation')) return "Entretien, réparation et dépannage";
                        if (str_contains($n, 'aliment') || str_contains($n, 'nourriture') || str_contains($n, 'restaurant') || str_contains($n, 'repas')) return "Courses, supermarchés, restauration";
                        if (str_contains($n, 'transport') || str_contains($n, 'carburant') || str_contains($n, 'voyage') || str_contains($n, 'véhicule')) return "Carburant, abonnement bus, train, billets";
                        if (str_contains($n, 'dividende') || str_contains($n, 'investissement') || str_contains($n, 'épargne') || str_contains($n, 'banque')) return "Épargne, intérêts et retours sur investissements";
                        if (str_contains($n, 'logement') || str_contains($n, 'loyer') || str_contains($n, 'maison')) return "Loyer, charges de copropriété, travaux";
                        if (str_contains($n, 'santé') || str_contains($n, 'pharmacie') || str_contains($n, 'médecin')) return "Frais médicaux, pharmacie, assurance santé";
                        if (str_contains($n, 'loisir') || str_contains($n, 'divertissement') || str_contains($n, 'sortie') || str_contains($n, 'sport')) return "Cinéma, abonnements, activités sportives";
                        if (str_contains($n, 'facture') || str_contains($n, 'abonnement') || str_contains($n, 'internet') || str_contains($n, 'eau') || str_contains($n, 'électricité')) return "Eau, gaz, électricité, internet, forfaits";
                        if (str_contains($n, 'vêtement') || str_contains($n, 'shopping') || str_contains($n, 'habit')) return "Achat de vêtements, chaussures, accessoires";
                        if (str_contains($n, 'éducation') || str_contains($n, 'école') || str_contains($n, 'scolarité')) return "Frais de scolarité, livres, fournitures";
                        if (str_contains($n, 'cadeau')) return "Cadeaux offerts, dons associatifs";
                        
                        return "Dépenses ou revenus divers liés à cette catégorie";
                    }
                    function getIcon($nom) {
                        $n = strtolower($nom);
                        // Salaire / Revenus
                        if (str_contains($n, 'salaire')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>';
                        
                        // Vente
                        if (str_contains($n, 'vente')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
                        
                        // Contribution / Remboursement
                        if (str_contains($n, 'contribution') || str_contains($n, 'remboursement') || str_contains($n, 'don')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>';
                        
                        // Virement / Transfert
                        if (str_contains($n, 'virement') || str_contains($n, 'transfert')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>';
                        
                        // Fournitures
                        if (str_contains($n, 'fourniture')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z"/></svg>';
                        
                        // Maintenance / Réparation
                        if (str_contains($n, 'maintenance') || str_contains($n, 'réparation')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';
                        
                        // Alimentation
                        if (str_contains($n, 'aliment') || str_contains($n, 'nourriture') || str_contains($n, 'restaurant') || str_contains($n, 'repas')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>';
                        
                        // Transport
                        if (str_contains($n, 'transport') || str_contains($n, 'carburant') || str_contains($n, 'voyage') || str_contains($n, 'véhicule')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>';
                        
                        // Logement
                        if (str_contains($n, 'logement') || str_contains($n, 'loyer') || str_contains($n, 'maison')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>';
                        
                        // Santé
                        if (str_contains($n, 'santé') || str_contains($n, 'pharmacie') || str_contains($n, 'médecin')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>';
                        
                        // Loisirs / Divertissement
                        if (str_contains($n, 'loisir') || str_contains($n, 'divertissement') || str_contains($n, 'sortie') || str_contains($n, 'sport')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>';
                        
                        // Éducation
                        if (str_contains($n, 'éducation') || str_contains($n, 'école') || str_contains($n, 'scolarité')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>';
                        
                        // Factures
                        if (str_contains($n, 'facture') || str_contains($n, 'abonnement') || str_contains($n, 'internet') || str_contains($n, 'eau') || str_contains($n, 'électricité')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>';
                        
                        // Shopping / Vêtements
                        if (str_contains($n, 'vêtement') || str_contains($n, 'shopping') || str_contains($n, 'habit')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>';
                        
                        // Cadeaux / Dons
                        if (str_contains($n, 'cadeau')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>';
                        
                        // Épargne / Investissement
                        if (str_contains($n, 'dividende') || str_contains($n, 'investissement') || str_contains($n, 'épargne') || str_contains($n, 'banque')) return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>';
                        
                        // Défaut
                        return '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>';
                    }
                    @endphp
                    @forelse($categories as $cat)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors last:border-0">
                        <td class="px-8 py-6 whitespace-nowrap">
                            @php 
                                $isEntree = $cat->type === 'entrée';
                                $bgIcon = $isEntree ? 'bg-[#e5fcf6] text-[#0d9488]' : 'bg-[#fff1f2] text-[#e11d48]'; 
                            @endphp
                            <div class="w-10 h-10 rounded-[10px] {{ $bgIcon }} flex items-center justify-center">
                                {!! getIcon($cat->nom) !!}
                            </div>
                        </td>
                        <td class="py-6 whitespace-nowrap">
                            <span class="font-extrabold text-[#0f172a] text-[1rem]">{{ $cat->nom }}</span>
                        </td>
                        <td class="hidden md:table-cell py-6 whitespace-nowrap">
                            <span class="italic text-slate-500 font-medium text-[0.95rem]">{{ getDesc($cat->nom) }}</span>
                        </td>
                        <td class="py-6 whitespace-nowrap">
                            @if($isEntree)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[0.7rem] font-bold rounded-full bg-[#f0fdfa] text-[#0d9488] border border-teal-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg> Entrée
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[0.7rem] font-bold rounded-full bg-[#fff1f2] text-[#e11d48] border border-rose-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg> Sortie
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-5">
                                <button type="button" onclick="openEditCategoryModal({{ $cat->id }}, '{{ addslashes($cat->nom) }}', '{{ $cat->type }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 000-1.41l-2.34-2.34a.996.996 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                </button>
                                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="m-0 inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" onclick="return confirm('Confirmer la suppression ?')">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold">
                            Aucune catégorie trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination Mockup styled Area -->
            <div class="bg-white p-5 flex justify-between items-center px-8 border-t border-slate-100">
                <div class="text-[0.95rem] font-medium text-slate-600">
                    Affichage de <span class="font-extrabold text-[#0f172a]">1</span> à <span class="font-extrabold text-[#0f172a]">{{ min(4, count($categories)) }}</span> sur <span class="font-extrabold text-[#0f172a]">{{ $toutes->count() }}</span> catégories
                </div>
                <!-- Pagination Links -->
                <div class="flex items-center gap-1.5">
                    <a href="#" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors pointer-events-none opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-[8px] text-[0.9rem] font-bold bg-[#0d9488] text-white shadow-sm transition-all">1</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-[8px] text-[0.9rem] font-bold text-slate-600 hover:bg-slate-100 transition-all">2</a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-[8px] text-[0.9rem] font-bold text-slate-600 hover:bg-slate-100 transition-all">3</a>
                    
                    <a href="#" class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Optimization Banner -->
    <div class="bg-gradient-to-r from-[#0d9488] to-[#14b8a6] rounded-[24px] p-10 flex flex-col md:flex-row justify-between items-center gap-8 shadow-md">
        <div class="max-w-[550px] text-white">
            <h2 class="text-[1.6rem] font-extrabold mb-3 tracking-tight">Optimisez vos Analyses</h2>
            <p class="text-teal-50 text-[1rem] leading-relaxed font-medium opacity-90">Les catégories bien définies permettent des rapports financiers plus précis et une meilleure vision de votre santé budgétaire.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl p-4 pl-6 text-white self-stretch shadow-sm">
            <div class="flex -space-x-3">
                <img class="w-10 h-10 rounded-full border-2 border-[#14b8a6] object-cover shadow-sm bg-white" src="https://i.pravatar.cc/100?img=11" alt="Admin user">
                <img class="w-10 h-10 rounded-full border-2 border-[#14b8a6] object-cover shadow-sm bg-white" src="https://i.pravatar.cc/100?img=5" alt="Admin user">
                <img class="w-10 h-10 rounded-full border-2 border-[#14b8a6] object-cover shadow-sm bg-white" src="https://i.pravatar.cc/100?img=8" alt="Admin user">
            </div>
            <div class="ml-2">
                <p class="text-[0.95rem] font-extrabold">Conseil d'expert</p>
                <p class="text-[0.7rem] text-teal-100 font-bold tracking-wide">Plus de 1200 admins l'utilisent.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Catégorie -->
<div id="categoryModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-all opacity-0 px-4">
    <div class="bg-white rounded-[24px] p-6 md:p-8 shadow-2xl w-full max-w-md transform transition-all scale-95 border border-slate-100" id="categoryModalContent">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-extrabold text-[#0f172a]">Nouvelle Catégorie</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Organisez vos transactions</p>
            </div>
            <button onclick="closeCategoryModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="space-y-5 mb-8">
                <div>
                    <label class="block text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nom de la catégorie</label>
                    <input type="text" name="nom" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[0.95rem] font-bold outline-none focus:bg-white focus:border-[#0d9488] focus:ring-4 focus:ring-teal-500/10 transition-all text-[#0f172a]" placeholder="ex: Alimentation" autocomplete="off">
                </div>
                <div>
                    <label class="block text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Type d'opération</label>
                    <div class="relative">
                        <select name="type" required class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[0.95rem] font-bold outline-none focus:bg-white focus:border-[#0d9488] focus:ring-4 focus:ring-teal-500/10 transition-all text-[#0f172a]">
                            <option value="sortie">Dépense (Sortie)</option>
                            <option value="entrée">Revenu (Entrée)</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="closeCategoryModal()" class="w-full sm:flex-1 py-3.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-extrabold text-[0.95rem] hover:bg-slate-50 transition-colors">Annuler</button>
                <button type="submit" class="w-full sm:flex-1 py-3.5 bg-[#0d9488] hover:bg-[#0f766e] text-white rounded-xl font-extrabold text-[0.95rem] shadow-md shadow-teal-500/20 transition-colors">Créer la catégorie</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modification Catégorie -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-all opacity-0 px-4">
    <div class="bg-white rounded-[24px] p-6 md:p-8 shadow-2xl w-full max-w-md transform transition-all scale-95 border border-slate-100" id="editCategoryModalContent">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-extrabold text-[#0f172a]">Modifier la Catégorie</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Mettez à jour les informations</p>
            </div>
            <button type="button" onclick="closeEditCategoryModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form action="" method="POST" id="editCategoryForm">
            @csrf
            @method('PUT')
            <div class="space-y-5 mb-8">
                <div>
                    <label class="block text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nom de la catégorie</label>
                    <input type="text" name="nom" id="edit_nom" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[0.95rem] font-bold outline-none focus:bg-white focus:border-[#0d9488] focus:ring-4 focus:ring-teal-500/10 transition-all text-[#0f172a]" autocomplete="off">
                </div>
                <div>
                    <label class="block text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Type d'opération</label>
                    <div class="relative">
                        <select name="type" id="edit_type" required class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[0.95rem] font-bold outline-none focus:bg-white focus:border-[#0d9488] focus:ring-4 focus:ring-teal-500/10 transition-all text-[#0f172a]">
                            <option value="sortie">Dépense (Sortie)</option>
                            <option value="entrée">Revenu (Entrée)</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="closeEditCategoryModal()" class="w-full sm:flex-1 py-3.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-extrabold text-[0.95rem] hover:bg-slate-50 transition-colors">Annuler</button>
                <button type="submit" class="w-full sm:flex-1 py-3.5 bg-[#0d9488] hover:bg-[#0f766e] text-white rounded-xl font-extrabold text-[0.95rem] shadow-md shadow-teal-500/20 transition-colors">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('categoryModal');
    const modalContent = document.getElementById('categoryModalContent');

    function openCategoryModal() {
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
    }

    function closeCategoryModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Close on click outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeCategoryModal();
        }
    });

    // Edit Modal logic
    const editModal = document.getElementById('editCategoryModal');
    const editModalContent = document.getElementById('editCategoryModalContent');

    function openEditCategoryModal(id, nom, type) {
        document.getElementById('edit_nom').value = nom;
        document.getElementById('edit_type').value = type;
        document.getElementById('editCategoryForm').action = "/categories/" + id;
        
        editModal.classList.remove('hidden');
        void editModal.offsetWidth;
        editModal.classList.remove('opacity-0');
        editModalContent.classList.remove('scale-95');
    }

    function closeEditCategoryModal() {
        editModal.classList.add('opacity-0');
        editModalContent.classList.add('scale-95');
        setTimeout(() => {
            editModal.classList.add('hidden');
        }, 300);
    }

    editModal.addEventListener('click', function(e) {
        if (e.target === editModal) {
            closeEditCategoryModal();
        }
    });
</script>
@endsection
