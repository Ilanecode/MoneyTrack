@extends('layouts.app')

@section('title', 'Paramètres')
@section('page_title', 'Paramètres')
@section('page_subtitle', 'Gérez votre sécurité et vos préférences système')

@section('content')
<div class="max-w-[1000px] mx-auto pb-12 pt-4">

    <!-- Flash messages -->
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-[#e5fcf6] border border-[#ccfbf1] rounded-[12px] text-[#0f766e] font-bold text-[0.9rem]">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-red-50 border border-red-100 rounded-[12px] text-red-600 font-bold text-[0.9rem]">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Budget Mensuel Configurable -->
    <div class="bg-white rounded-[24px] p-8 shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-[12px] bg-[#e0f2fe] flex items-center justify-center text-[#0284c7]">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-[1.15rem] text-[#0f766e] tracking-tight">Budget Mensuel de Dépenses</h3>
                <p class="text-[0.85rem] font-medium text-slate-500">Définissez le plafond de dépenses mensuel (en FCFA)</p>
            </div>
        </div>
        <form action="{{ route('settings.budget') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-[0.75rem] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Montant en F CFA</label>
                <div class="relative">
                    <input
                        type="number"
                        name="budget_mensuel"
                        value="{{ $budgetMensuel }}"
                        min="0"
                        step="1000"
                        class="w-full bg-[#f8fafc] border border-slate-200 rounded-[12px] pl-9 pr-4 py-3 text-[0.95rem] font-bold text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-[#0d9488] transition-all"
                        required>
                    <span class="absolute right-1 top-1/2 -translate-y-1/2 text-slate-500 font-black text-[0.9rem]">F</span>    
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-[#0d9488] hover:bg-[#0f766e] text-white font-bold text-[0.9rem] rounded-[12px] shadow-sm transition-all whitespace-nowrap">
                Enregistrer
            </button>
        </form>
    </div>

    <!-- Top Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        <!-- Sauvegarde Automatique -->
        <div class="lg:col-span-3 bg-white rounded-[24px] p-8 shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 flex flex-col">
            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-[12px] bg-[#e5fcf6] flex items-center justify-center text-[#0d9488]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-[1.15rem] text-[#0f766e] tracking-tight">Sauvegarde Automatique</h3>
                        <p class="text-[0.85rem] font-medium text-slate-500">Sécurisez vos données sur le cloud</p>
                    </div>
                </div>
                <!-- Toggle -->
                <button class="w-12 h-6 rounded-full bg-[#0d9488] relative transition-colors focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2">
                    <div class="w-4 h-4 rounded-full bg-white absolute right-1 top-1 transition-transform"></div>
                </button>
            </div>

            <div class="bg-[#f8fafc] border border-slate-100 rounded-[16px] p-5 mb-5 flex justify-between items-center bg-opacity-70">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <div class="flex flex-col">
                        <span class="font-bold text-[0.9rem] text-[#0f766e]">Dernière sauvegarde</span>
                        @if($latestBackup)
                        <span class="text-[0.75rem] text-slate-500">{{ round($latestBackup['file_size'] / 1024) }} KB</span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    @if($latestBackup)
                    <div class="font-extrabold text-[0.9rem] text-[#0f172a]">{{ \Carbon\Carbon::createFromTimestamp($latestBackup['last_modified'])->format('d M Y, H:i') }}</div>
                    <a href="{{ route('settings.backup.download', $latestBackup['file_name']) }}" class="font-bold text-[0.7rem] text-[#0d9488] hover:underline cursor-pointer">Télécharger ZIP ↓</a>
                    @else
                    <div class="font-extrabold text-[0.9rem] text-[#0f172a]">Aucune</div>
                    <div class="font-bold text-[0.7rem] text-slate-400">En attente</div>
                    @endif
                </div>
            </div>

            <form action="{{ route('settings.backup') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" onclick="this.innerHTML='Création en cours...'; this.classList.add('opacity-50')" class="w-full py-4 bg-white border border-slate-200 rounded-[12px] text-[#0f172a] font-bold text-[0.95rem] hover:bg-[#f8fafc] transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Sauvegarder Maintenant
                </button>
            </form>
        </div>

        <!-- Restauration -->
        <div class="lg:col-span-2 bg-white rounded-[24px] p-8 shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 flex flex-col items-start">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-[12px] bg-[#e5fcf6] flex items-center justify-center text-[#0d9488]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[1.15rem] text-[#0f766e] tracking-tight">Restauration</h3>
                    <p class="text-[0.8rem] font-medium text-slate-500">Importer des archives</p>
                </div>
            </div>

            <p class="text-[0.85rem] text-slate-500 font-medium leading-relaxed mb-4 flex-1">
                Restaurez vos transactions à partir d'archive `.zip` ou fichier `.sql`. Attention, le système va s'écraser !
            </p>

            <form action="{{ route('settings.restore') }}" method="POST" enctype="multipart/form-data" class="w-full" onsubmit="return confirm('⚠️ DANGER : Êtes-vous certain(e) de vouloir restaurer cette base de données ? Toutes vos données actuelles seront DÉFINITIVEMENT ÉCRASÉES par le fichier importé. L\'opération est irréversible.');">
                @csrf
                <div class="mb-3 w-full border-2 border-dashed border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:bg-slate-50 transition" onclick="document.getElementById('backup_file').click()">
                    <input type="file" id="backup_file" name="backup_file" accept=".zip,.sql" class="hidden" required onchange="document.getElementById('file-name').innerHTML = this.files[0].name" />
                    <span id="file-name" class="text-[0.8rem] font-bold text-slate-400">Choisir un fichier zip ou sql</span>
                </div>
                <button type="submit" onclick="if(document.getElementById('backup_file').files.length > 0) { this.innerHTML='Restauration en cours...'; }" class="w-full py-4 bg-[#14b8a6] hover:bg-[#0d9488] text-white rounded-[12px] font-bold text-[0.95rem] shadow-sm transition-colors flex items-center justify-center gap-2 mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                    Importer les Données
                </button>
            </form>
            <p class="w-full text-center text-[0.65rem] font-black uppercase tracking-widest text-[#0f172a]">
                TAILLE MAX: 50MB
            </p>
        </div>
    </div>

    <!-- Gestion des Permissions -->
    <div class="bg-white rounded-[24px] p-8 shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 mb-6">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-[12px] bg-[#ffedd5] flex items-center justify-center text-[#c2410c]">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-[1.15rem] text-[#0f766e] tracking-tight">Gestion des Permissions</h3>
                <p class="text-[0.85rem] font-medium text-slate-500">Contrôlez l'accès de l'application à votre appareil</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Localisation -->
            <div class="bg-[#f8fafc] border border-slate-100 rounded-[16px] p-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-[#e2e8f0] rounded-full flex items-center justify-center text-[#475569]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="font-extrabold text-[0.95rem] text-[#0f766e]">Localisation</span>
                </div>
                <!-- Toggle -->
                <button class="w-12 h-6 rounded-full bg-[#0d9488] relative transition-colors focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2">
                    <div class="w-4 h-4 rounded-full bg-white absolute right-1 top-1 transition-transform"></div>
                </button>
            </div>

            <!-- Notifications -->
            <div class="bg-[#f8fafc] border border-slate-100 rounded-[16px] p-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-[#e2e8f0] rounded-full flex items-center justify-center text-[#475569]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                    </div>
                    <span class="font-extrabold text-[0.95rem] text-[#0f766e]">Notifications</span>
                </div>
                <!-- Toggle -->
                <button class="w-12 h-6 rounded-full bg-[#0d9488] relative transition-colors focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2">
                    <div class="w-4 h-4 rounded-full bg-white absolute right-1 top-1 transition-transform"></div>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
