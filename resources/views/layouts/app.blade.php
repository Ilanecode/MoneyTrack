<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTrack - @yield('title', 'Gestion de Caisse')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230f766e'/%3E%3Crect x='4' y='18' width='5' height='10' rx='1.5' fill='white'/%3E%3Crect x='11' y='12' width='5' height='16' rx='1.5' fill='white' opacity='.85'/%3E%3Crect x='18' y='7' width='5' height='21' rx='1.5' fill='white'/%3E%3Crect x='25' y='14' width='3' height='14' rx='1.5' fill='white' opacity='.7'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Polices professionnelles : Plus Jakarta Sans (interface) + Inter (chiffres) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* Personnalisation de la barre de défilement*/
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #0f766e;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0d9488;
        }
        
        /* Firefox*/
        * {
            scrollbar-width: thin;
            scrollbar-color: #0f766e #f1f5f9;
        }

        /* Animation de la cloche */
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50% { transform: rotate(15deg); }
            20%, 40% { transform: rotate(-15deg); }
            60% { transform: rotate(0deg); }
        }
        .animate-ring {
            animation: ring 3s ease-in-out infinite;
            transform-origin: top center;
        }
    </style>
</head>
<body class="font-sans bg-[#e2e8f0] text-slate-900 antialiased overflow-x-hidden">
@auth
<div class="flex min-h-screen relative">

    {{-- ════ MOBILE BACKDROP ════ --}}
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    {{-- ════ SIDEBAR ════ --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-[240px] bg-[#0f766e] flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        {{-- Logo Area --}}
        <div class="px-6 py-8 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10v7h3v-7H4zm6 0v7h3v-7h-3zM2 22h19v-3H2v3zm14-12v7h3v-7h-3zm-4.5-9L2 6v2h19V6l-9.5-5z"/></svg>
            </div>
            <div>
                <div class="text-[1.2rem] font-extrabold text-white tracking-tight leading-none">MoneyTrack</div>
                <div class="text-[0.6rem] font-bold text-white/50 tracking-[0.15em] uppercase mt-1">Precision Wealth</div>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 px-4 py-2 flex flex-col gap-1.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Tableau de bord
            </a>
            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('projects.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Projets
            </a>
            <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('transactions.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Transactions
            </a>


            @if(auth()->user() && auth()->user()->isAdmin())
            <div class="text-[0.6rem] font-bold tracking-[0.18em] uppercase text-white/40 px-4 pt-6 pb-2">Administration</div>
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('reports.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                Rapports
            </a>
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                Catégories
            </a>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                Utilisateurs
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[0.88rem] font-semibold transition-all {{ request()->routeIs('settings.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Paramètres
            </a>
            @endif
        </nav>

        {{-- Footer / Logout --}}
        <div class="p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">@csrf</form>
            <button class="flex items-center gap-3 px-4 py-3 w-full rounded-xl text-[0.88rem] font-semibold text-white/70 hover:bg-white/10 hover:text-white transition-all focus:outline-none" onclick="document.getElementById('logout-form').submit()">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Déconnexion
            </button>
        </div>
    </aside>

    {{-- ════ MAIN CONTENT ════ --}}
    <main class="ml-0 lg:ml-[240px] flex-1 flex flex-col min-h-screen transition-all duration-300">
        
        {{-- Top Header --}}
        <header class="sticky top-0 z-30 bg-[#e2e8f0]/95 backdrop-blur-sm border-b border-slate-300 lg:border-none lg:bg-transparent lg:backdrop-blur-none lg:static px-5 lg:px-10 py-4 lg:pt-10 lg:pb-6 flex justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-[#0f766e] rounded-xl hover:bg-slate-200 focus:outline-none transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                @hasSection('page_title')
                <div>
                    <h1 class="text-[1.5rem] lg:text-[1.8rem] font-extrabold text-[#0f766e] tracking-tight leading-tight">@yield('page_title')</h1>
                    <p class="text-[0.75rem] lg:text-[0.85rem] text-slate-500 mt-0.5 font-medium">@yield('page_subtitle')</p>
                </div>
                @else
                <div></div>
                @endif
            </div>
            
            <div class="flex items-center gap-4">
                {{-- Notifications --}}
                <div class="relative">
                    @php
                        if (auth()->user()->isAdmin()) {
                            $pendingNotifs = \App\Models\Transaction::where('statut', 'en_attente')->latest()->take(5)->get();
                            $notifCount = \App\Models\Transaction::where('statut', 'en_attente')->count();
                        } else {
                            $pendingNotifs = \App\Models\Transaction::where('statut', 'en_attente')->where('user_id', auth()->id())->latest()->take(5)->get();
                            $notifCount = \App\Models\Transaction::where('statut', 'en_attente')->where('user_id', auth()->id())->count();
                        }
                    @endphp
                    <button id="notifBtn" class="relative w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-500 shadow-sm border border-slate-100 hover:shadow-md hover:text-[#0d9488] transition-all focus:outline-none">
                        <svg class="w-5 h-5 {{ $notifCount > 0 ? 'animate-ring text-[#0d9488]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($notifCount > 0)
                        <span class="absolute top-[6px] right-[6px] flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white shadow-sm"></span>
                        </span>
                        @endif
                    </button>
                    <!-- Dropdown Notif -->
                    <div id="notifMenu" class="hidden absolute top-[calc(100%+10px)] right-0 w-80 bg-white rounded-2xl shadow-[0_15px_50px_rgba(0,0,0,0.12)] border border-slate-100 z-50 overflow-hidden transform opacity-100 transition-all duration-200">
                        <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <span class="text-[0.7rem] font-extrabold tracking-widest uppercase text-slate-400">Notifications</span>
                            @if($notifCount > 0)
                            <span class="bg-red-100 text-red-600 text-[0.6rem] font-black px-2 py-0.5 rounded-full">{{ $notifCount }}</span>
                            @endif
                        </div>
                        <div class="max-h-[300px] overflow-y-auto">
                            @forelse($pendingNotifs as $notif)
                                @if(auth()->user()->isAdmin())
                                    <div class="block px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors group">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-[0.75rem] font-bold text-slate-800 mb-0.5 leading-tight group-hover:text-teal-600 transition-colors">Approbation requise</div>
                                                <div class="text-[0.7rem] text-slate-500 leading-snug mb-2">Dépense de {{ number_format($notif->montant, 0, ',', ' ') }} F demandée par <span class="font-bold text-slate-700">{{ $notif->user->name ?? 'Caissier' }}</span>.</div>
                                                <div class="flex gap-2">
                                                    <form action="{{ route('transactions.approve', $notif->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 bg-green-100 text-green-700 text-[0.65rem] font-bold rounded shadow-sm hover:bg-green-200 transition-colors">Approuver</button>
                                                    </form>
                                                    <form action="{{ route('transactions.reject', $notif->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 bg-red-100 text-red-700 text-[0.65rem] font-bold rounded shadow-sm hover:bg-red-200 transition-colors">Rejeter</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="px-4 py-3 border-b border-slate-50 flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-[0.75rem] font-bold text-slate-700 mb-0.5 leading-tight">En attente d'approbation</div>
                                            <div class="text-[0.7rem] text-slate-500 leading-snug">Votre dépense de {{ number_format($notif->montant, 0, ',', ' ') }} F a été envoyée à l'administrateur.</div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-[0.75rem] font-bold text-slate-400">Aucune notification</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- User Avatar --}}
                <div class="relative">
                    <button id="profileBtn" class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-sm shadow-sm border border-white hover:shadow-md transition-all focus:outline-none overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=0d9488&color=ffffff" alt="User" class="w-full h-full object-cover">
                    </button>
                    <!-- Dropdown Profile -->
                    <div id="profileMenu" class="hidden absolute top-[calc(100%+10px)] right-0 w-56 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 z-50 p-2">
                        <div class="p-3 bg-slate-50 rounded-xl mb-2">
                            <div class="font-bold text-[0.85rem] text-slate-900 truncate">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                            <div class="text-[0.72rem] text-slate-500 truncate mt-0.5">{{ auth()->user()->email ?? '' }}</div>
                            <span class="inline-block mt-2 px-2.5 py-0.5 bg-teal-100/50 text-teal-700 text-[0.65rem] font-bold rounded-full uppercase tracking-wider">{{ auth()->user()->role ?? 'Role' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Page Content --}}
        <div class="px-4 lg:px-10 pb-12 flex-1 w-full">
            {{-- Alertes Flash --}}
            @if(session('success'))
                <div class="mb-6 flex items-center justify-between bg-white border-l-4 border-green-500 rounded-xl p-4 text-[0.85rem] font-semibold text-green-800 shadow-sm">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 focus:outline-none font-bold text-lg leading-none">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-center justify-between bg-white border-l-4 border-red-500 rounded-xl p-4 text-[0.85rem] font-semibold text-red-800 shadow-sm">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 focus:outline-none font-bold text-lg leading-none">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>

    </main>

</div>
@else
    @yield('content')
@endauth

<script>
    // System for dropdowns
    const notifBtn = document.getElementById('notifBtn');
    const profileBtn = document.getElementById('profileBtn');
    const notifMenu = document.getElementById('notifMenu');
    const profileMenu = document.getElementById('profileMenu');

    if(notifBtn && notifMenu && profileBtn && profileMenu) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.add('hidden');
            notifMenu.classList.toggle('hidden');
        });
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifMenu.classList.add('hidden');
            profileMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', () => {
            notifMenu.classList.add('hidden');
            profileMenu.classList.add('hidden');
        });
    }

    // Sidebar Toggle for Mobile
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    let isSidebarOpen = false;

    function toggleSidebar() {
        if(!sidebar || !sidebarBackdrop) return;
        isSidebarOpen = !isSidebarOpen;
        if(isSidebarOpen) {
            sidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
            setTimeout(() => {
                sidebarBackdrop.classList.remove('opacity-0');
            }, 10);
            document.body.classList.add('overflow-hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('opacity-0');
            setTimeout(() => {
                sidebarBackdrop.classList.add('hidden');
            }, 300);
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>
</body>
</html>
