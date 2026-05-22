@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page_title', 'Gestion des Utilisateurs')
@section('page_subtitle', 'Gérez les accès, rôles et privilèges des collaborateurs de la plateforme MoneyTrack.')

@section('content')
<div class="max-w-[1100px] mx-auto pb-12 pt-4">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row justify-end gap-4 mb-10 w-full sm:w-auto">
        <a href="{{ route('audits.index') }}" class="w-full sm:w-auto justify-center flex items-center gap-2.5 px-6 py-3.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-[10px] text-[0.95rem] font-bold shadow-sm transition-all focus:ring-4 focus:ring-slate-500/10 outline-none hover:border-[#0f766e] rounded-[10px] p-2 transition-all duration-300">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Journal
        </a>
        <button onclick="toggleModal('userModal')" class="w-full sm:w-auto justify-center flex items-center gap-2.5 px-6 py-3.5 bg-[#0d9488] hover:bg-[#0f766e] text-white rounded-[10px] text-[0.95rem] font-bold shadow-sm transition-all focus:ring-4 focus:ring-teal-500/20 outline-none">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a5 5 0 015 5v1H1v-1a5 5 0 015-5zM13 14a1 1 0 100-2 1 1 0 000 2z"></path></svg>
            Créer un utilisateur
        </button>
    </div>

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

    <!-- Stats rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-[14px] border border-slate-100 p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-[#e5fcf6] flex items-center justify-center text-[#0d9488]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
            </div>
            <div>
                <p class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">Total utilisateurs</p>
                <p class="text-[1.6rem] font-black text-[#0f172a] tracking-tight">{{ $totalUsersCount }}</p>
            </div>
        </div>
        <div class="bg-white rounded-[14px] border border-slate-100 p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-[#e5fcf6] flex items-center justify-center text-[#0d9488]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <p class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">Administrateurs</p>
                <p class="text-[1.6rem] font-black text-[#0f172a] tracking-tight">{{ $users->getCollection()->where('role', 'admin')->count() + \App\Models\User::where('role','admin')->count() - $users->getCollection()->where('role', 'admin')->count() }}</p>
            </div>
        </div>
        <div class="bg-[#0f766e] rounded-[14px] p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
            </div>
            <div>
                <p class="text-[0.7rem] font-black text-white uppercase tracking-widest">Caissiers</p>
                <p class="text-[1.6rem] font-black text-white tracking-tight">{{ \App\Models\User::where('role','caissier')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[16px] shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden mb-8">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-white">
                        <th class="px-8 py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest w-72 whitespace-nowrap">Nom</th>
                        <th class="hidden md:table-cell py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest whitespace-nowrap">Email</th>
                        <th class="py-5 text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest w-48 whitespace-nowrap">Rôle</th>
                        <th class="px-8 py-5 text-right text-[0.7rem] font-extrabold text-slate-500 uppercase tracking-widest w-32 whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function getRoleBadge($role) {
                            $r = strtolower($role);
                            if (str_contains($r, 'admin')) return 'bg-[#e5fcf6] text-[#0d9488] border border-[#ccfbf1]';
                            if (str_contains($r, 'caissier')) return 'bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd]';
                            return 'bg-[#f1f5f9] text-[#475569] border border-[#e2e8f0]';
                        }
                        function getInitialsColor($idx) {
                            $colors = [
                                'bg-[#ccfbf1] text-[#0f766e]',
                                'bg-[#86efac] text-[#166534]',
                                'bg-[#fed7aa] text-[#c2410c]',
                                'bg-[#bae6fd] text-[#0369a1]',
                                'bg-[#fbcfe8] text-[#be185d]',
                            ];
                            return $colors[$idx % count($colors)];
                        }
                    @endphp

                    @forelse($users as $idx => $user)
                    <tr class="border-b border-slate-50 hover:bg-[#f8fafc]/50 transition-colors last:border-0 group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full {{ getInitialsColor($idx) }} flex items-center justify-center font-black text-[0.8rem] tracking-widest">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="font-extrabold text-[#0f172a] text-[0.95rem] tracking-tight">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="hidden md:table-cell py-6 whitespace-nowrap">
                            <span class="text-slate-600 font-medium text-[0.95rem]">{{ $user->email }}</span>
                        </td>
                        <td class="py-6 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest rounded-full {{ getRoleBadge($user->role) }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <!-- Bouton Modifier -->
                                <button
                                    onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->role }}')"
                                    class="text-slate-400 hover:text-[#0d9488] transition-colors"
                                    title="Modifier">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 000-1.41l-2.34-2.34a.996.996 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                </button>
                                <!-- Bouton Supprimer (pas sur soi-même) -->
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="m-0 inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Confirmer la suppression de cet utilisateur ?')" class="text-slate-400 hover:text-red-500 transition-colors" title="Supprimer">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold">
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="bg-white p-5 px-8 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div id="userModal" class="hidden fixed inset-0 bg-slate-900/60 z-[1000] flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-[450px] p-6 md:p-8 rounded-[20px] shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-extrabold text-[1.4rem] text-[#0f172a] tracking-tight">Nouvel Utilisateur</h3>
            <button onclick="toggleModal('userModal')" class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#f1f5f9] text-slate-500 hover:text-slate-800 hover:bg-[#e2e8f0] transition-colors text-xl font-bold">&times;</button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[0.75rem] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Nom Complet</label>
                    <input type="text" name="name" class="w-full bg-[#f8fafc] border border-slate-200 rounded-[12px] px-4 py-3 text-[0.95rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-[#0d9488] transition-all" required placeholder="ex: Jean Dupont">
                </div>
                <div>
                    <label class="block text-[0.75rem] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Adresse E-mail</label>
                    <input type="email" name="email" class="w-full bg-[#f8fafc] border border-slate-200 rounded-[12px] px-4 py-3 text-[0.95rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-[#0d9488] transition-all" required placeholder="ex: jean@exemple.com">
                </div>
                <div>
                    <label class="block text-[0.75rem] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Rôle</label>
                    <div class="relative">
                        <select name="role" class="w-full bg-[#f8fafc] border border-slate-200 rounded-[12px] px-4 py-3 text-[0.95rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-[#0d9488] transition-all appearance-none cursor-pointer" required>
                            <option value="caissier">Caissier (Accès limité)</option>
                            <option value="admin">Administrateur (Contrôle total)</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div>
                    <label class="block text-[0.75rem] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Mot de Passe Provisoire</label>
                    <input type="password" name="password" class="w-full bg-[#f8fafc] border border-slate-200 rounded-[12px] px-4 py-3 text-[0.95rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-[#0d9488] transition-all" required placeholder="••••••••">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <button type="submit" class="w-full sm:flex-1 px-4 py-3 rounded-[12px] bg-[#0d9488] text-white font-bold text-[0.9rem] shadow-sm hover:bg-[#0f766e] transition-all">Créer le compte</button>
                <button type="button" onclick="toggleModal('userModal')" class="w-full sm:flex-1 px-4 py-3 rounded-[12px] bg-[#f1f5f9] text-slate-600 font-bold text-[0.9rem] hover:bg-[#e2e8f0] hover:text-slate-800 transition-all">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Édition -->
<div id="editUserModal" class="hidden fixed inset-0 bg-slate-900/60 z-[1000] flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-[450px] p-6 md:p-8 rounded-[20px] shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-extrabold text-[1.4rem] text-[#0f172a] tracking-tight">Modifier l'utilisateur</h3>
                <p id="editUserName" class="text-sm text-slate-400 font-medium mt-0.5"></p>
            </div>
            <button onclick="toggleModal('editUserModal')" class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#f1f5f9] text-slate-500 hover:text-slate-800 hover:bg-[#e2e8f0] transition-colors text-xl font-bold">&times;</button>
        </div>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-[0.75rem] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Rôle</label>
                    <div class="relative">
                        <select id="editUserRole" name="role" class="w-full bg-[#f8fafc] border border-slate-200 rounded-[12px] px-4 py-3 text-[0.95rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-[#0d9488] transition-all appearance-none cursor-pointer" required>
                            <option value="caissier">Caissier (Accès limité)</option>
                            <option value="admin">Administrateur (Contrôle total)</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-[12px] p-4">
                    <p class="text-[0.75rem] font-extrabold text-amber-700 uppercase tracking-widest mb-3">Réinitialiser le mot de passe (optionnel)</p>
                    <div class="space-y-3">
                        <input type="password" name="password" placeholder="Nouveau mot de passe (min. 8 caractères)" class="w-full bg-white border border-amber-200 rounded-[10px] px-4 py-2.5 text-[0.9rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-amber-400/10 focus:border-amber-400 transition-all">
                        <input type="password" name="password_confirmation" placeholder="Confirmer le nouveau mot de passe" class="w-full bg-white border border-amber-200 rounded-[10px] px-4 py-2.5 text-[0.9rem] font-medium text-slate-800 outline-none focus:ring-4 focus:ring-amber-400/10 focus:border-amber-400 transition-all">
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <button type="submit" class="w-full sm:flex-1 px-4 py-3 rounded-[12px] bg-[#0d9488] text-white font-bold text-[0.9rem] shadow-sm hover:bg-[#0f766e] transition-all">Enregistrer</button>
                <button type="button" onclick="toggleModal('editUserModal')" class="w-full sm:flex-1 px-4 py-3 rounded-[12px] bg-[#f1f5f9] text-slate-600 font-bold text-[0.9rem] hover:bg-[#e2e8f0] hover:text-slate-800 transition-all">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        const isHidden = modal.classList.contains('hidden');
        if (isHidden) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.onclick = function(e) {
                if (e.target === modal) toggleModal(id);
            };
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openEditModal(userId, userName, userRole) {
        document.getElementById('editUserName').textContent = userName;
        document.getElementById('editUserForm').action = '/users/' + userId;
        document.getElementById('editUserRole').value = userRole;
        // Réinitialiser les champs de mot de passe
        document.querySelectorAll('#editUserForm input[type="password"]').forEach(i => i.value = '');
        toggleModal('editUserModal');
    }
</script>
@endsection
