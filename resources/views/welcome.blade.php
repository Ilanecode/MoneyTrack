<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MoneyTrack — Gérez vos finances avec clarté</title>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230f766e'/%3E%3Crect x='4' y='18' width='5' height='10' rx='1.5' fill='white'/%3E%3Crect x='11' y='12' width='5' height='16' rx='1.5' fill='white' opacity='.85'/%3E%3Crect x='18' y='7' width='5' height='21' rx='1.5' fill='white'/%3E%3Crect x='25' y='14' width='3' height='14' rx='1.5' fill='white' opacity='.7'/%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* CSS RESET AND VARS */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal:#0d9488;
  --teal-dark:#0f766e;
  --teal-light:#ccfbf1;
  --ink:#0f172a;
  --muted:#64748b;
  --border:#e2e8f0;
  --bg-gradient:linear-gradient(160deg,#e6faf8 0%,#f0fdfa 40%,#e0f2fe 100%);
}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg-gradient);min-height:100vh;color:var(--ink);-webkit-font-smoothing:antialiased;overflow-x:hidden;}

/* ANIMATIONS */
@keyframes fadeUp{from{opacity:0;transform:translateY(32px)}to{opacity:1;transform:translateY(0)}}
@keyframes floatBlob{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-20px) scale(1.04)}}
@keyframes floatBlobR{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(16px) scale(0.97)}}
@keyframes pulse{0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(13,148,136,.4)}50%{opacity:.6;box-shadow:0 0 0 6px rgba(13,148,136,0)}}

/* SCROLLBAR */
::-webkit-scrollbar { width: 10px; height: 10px; }
::-webkit-scrollbar-track { background: #f1f5f9; }
::-webkit-scrollbar-thumb { background: var(--teal-dark); border-radius: 10px; border: 2px solid #f1f5f9; }
::-webkit-scrollbar-thumb:hover { background: var(--teal); }
* { scrollbar-width: thin; scrollbar-color: var(--teal-dark) #f1f5f9; }

.anim-fadeup{animation:fadeUp .8s cubic-bezier(.22,1,.36,1) both}
.anim-fadeup-delay-1{animation:fadeUp .8s .15s cubic-bezier(.22,1,.36,1) both}
.anim-fadeup-delay-2{animation:fadeUp .8s .3s cubic-bezier(.22,1,.36,1) both}

/* SCROLL REVEAL */
.reveal{opacity:0;transform:translateY(28px);transition:opacity .65s cubic-bezier(.22,1,.36,1),transform .65s cubic-bezier(.22,1,.36,1)}
.reveal.visible{opacity:1;transform:none}

/* TYPOGRAPHY AND BUTTONS */
h1,h2,h3,h4{font-weight:800;line-height:1.2;letter-spacing:-0.02em}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s ease;border-radius:12px;padding:14px 28px;font-size:1rem;}
.btn-sm{padding:10px 20px;font-size:0.9rem;}
.btn-lg{padding:16px 36px;font-size:1.1rem;}
.btn-primary{background:var(--teal);color:#fff;box-shadow:0 4px 16px rgba(13,148,136,.25)}
.btn-primary:hover{background:var(--teal-dark);transform:translateY(-2px);box-shadow:0 8px 24px rgba(13,148,136,.35)}
.btn-secondary{background:#fff;color:var(--ink);border:1.5px solid var(--border);box-shadow:0 2px 8px rgba(0,0,0,.04)}
.btn-secondary:hover{background:#f8fafc;transform:translateY(-2px)}
.btn-white{background:#fff;color:var(--teal-dark);box-shadow:0 4px 16px rgba(0,0,0,.1)}
.btn-white:hover{background:#f0fdfa;transform:translateY(-2px)}

/* NAV */
nav{position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(240,253,250,0.88);backdrop-filter:blur(14px);border-bottom:1px solid rgba(13,148,136,.12);transition:background .3s}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 24px;height:72px;display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;align-items:center;gap:12px;text-decoration:none}
.logo-icon{width:40px;height:40px;background:linear-gradient(135deg,var(--teal),var(--teal-dark));border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,.3)}
.logo-text-wrapper{display:flex;flex-direction:column;justify-content:center}
.logo-text{font-size:1.2rem;letter-spacing:-0.05em;font-weight:800;color:var(--ink);line-height:1}
.logo-sub{font-size:0.6rem;font-weight:800;color:var(--muted);letter-spacing:0.15em;text-transform:uppercase;margin-top:4px;line-height:1}
.nav-links{display:flex;align-items:center;gap:16px}
.nav-links a.ghost{color:var(--muted);text-decoration:none;font-weight:600;font-size:0.95rem;padding:8px 16px;border-radius:10px;transition:.2s}
.nav-links a.ghost:hover{color:var(--teal);background:#f1f5f9}
.nav-toggle{display:none;flex-direction:column;gap:5px;background:none;border:none;cursor:pointer;padding:6px;z-index:200}
.nav-toggle span{display:block;width:24px;height:2px;background:var(--ink);border-radius:2px;transition:all .3s}
.nav-toggle.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.nav-toggle.open span:nth-child(2){opacity:0}
.nav-toggle.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}

/* HERO */
.hero-section{position:relative;overflow:hidden;padding:140px 24px 80px;}
.blob{position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;opacity:.55}
.blob-1{width:480px;height:480px;background:radial-gradient(circle,rgba(13,148,136,.25),transparent);top:-120px;left:-100px;animation:floatBlob 8s ease-in-out infinite}
.blob-2{width:360px;height:360px;background:radial-gradient(circle,rgba(8,145,178,.2),transparent);top:80px;right:-80px;animation:floatBlobR 9s ease-in-out infinite}
.hero-container{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;position:relative;z-index:1}
.hero-content{max-width:540px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:var(--teal-light);color:var(--teal-dark);font-size:0.8rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:6px 16px;border-radius:100px;margin-bottom:24px;border:1px solid rgba(13,148,136,.15)}
.hero-badge span{width:8px;height:8px;background:var(--teal);border-radius:50%;animation:pulse 2s infinite}
.hero h1{font-size:3.5rem;margin-bottom:24px}
.hero h1 .accent{background:linear-gradient(135deg,var(--teal),#0891b2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:1.15rem;color:var(--muted);line-height:1.7;margin-bottom:40px}
.hero-cta{display:flex;gap:16px;flex-wrap:wrap}

/* MOCKUP (Hero Image replacement) */
.hero-mockup{position:relative;perspective:1000px}
.mockup-card{background:#fff;border-radius:24px;border:1px solid var(--border);box-shadow:0 32px 64px rgba(0,0,0,.08);overflow:hidden;transform:rotateY(-5deg) rotateX(5deg);transition:transform .5s ease}
.hero-mockup:hover .mockup-card{transform:rotateY(0) rotateX(0)}
.mockup-header{padding:16px 24px;background:#f8fafc;border-bottom:1px solid var(--border);display:flex;gap:8px}
.mockup-dot{width:12px;height:12px;border-radius:50%;background:#e2e8f0}
.mockup-dot:nth-child(1){background:#fc8181}
.mockup-dot:nth-child(2){background:#f6ad55}
.mockup-dot:nth-child(3){background:#68d391}
.mockup-body{padding:32px;display:flex;flex-direction:column;gap:20px;background:#f8fafc}
.m-card{background:#fff;padding:24px;border-radius:16px;border:1px solid var(--border);box-shadow:0 4px 12px rgba(0,0,0,.02)}
.m-title{font-size:0.8rem;text-transform:uppercase;color:var(--muted);font-weight:700;margin-bottom:8px}
.m-amount{font-size:2.4rem;font-weight:800;color:var(--ink)}
.m-stats{display:flex;gap:16px;margin-top:20px}
.m-stat{flex:1;background:#fff;padding:16px;border-radius:12px;border:1px solid var(--border)}
.m-stat-val{font-size:1.4rem;font-weight:800;margin-top:4px}
.m-stat.income .m-stat-val{color:var(--teal)}
.m-stat.expense .m-stat-val{color:#ef4444}

/* SECTIONS */
section{padding:100px 24px}
.container{max-width:1200px;margin:0 auto}
.section-tag{font-size:0.85rem;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:var(--teal);margin-bottom:16px;text-align:center;display:block}
.section-title{font-size:2.8rem;text-align:center;margin-bottom:24px}
.section-sub{font-size:1.15rem;color:var(--muted);text-align:center;max-width:640px;margin:0 auto 64px;line-height:1.7}

/* FEATURES */
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:32px}
.feat-card{background:#fff;border:1px solid rgba(13,148,136,.1);border-radius:24px;padding:40px 32px;transition:all .4s cubic-bezier(.22,1,.36,1)}
.feat-card:hover{transform:translateY(-8px);box-shadow:0 24px 48px rgba(13,148,136,.1);border-color:rgba(13,148,136,.3)}
.feat-icon{width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--teal-light),rgba(13,148,136,.05));color:var(--teal);display:flex;align-items:center;justify-content:center;margin-bottom:24px;transition:transform .4s}
.feat-card:hover .feat-icon{transform:scale(1.1) rotate(5deg)}
.feat-card h3{font-size:1.3rem;margin-bottom:16px}
.feat-card p{font-size:1rem;color:var(--muted);line-height:1.7}

/* HOW IT WORKS */
.steps-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:48px;position:relative}
.step{text-align:center;position:relative;z-index:1}
.step-num{width:72px;height:72px;border-radius:24px;background:linear-gradient(135deg,var(--teal),var(--teal-dark));color:#fff;font-size:1.8rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 12px 32px rgba(13,148,136,.3);transition:transform .4s}
.step:hover .step-num{transform:scale(1.1) rotate(-5deg)}
.step h3{font-size:1.3rem;margin-bottom:12px}
.step p{font-size:1rem;color:var(--muted);line-height:1.7}

/* CTA BOX */
.cta-box{background:var(--ink);border-radius:32px;padding:80px 40px;text-align:center;position:relative;overflow:hidden;margin-top:40px}
.cta-box::before{content:'';position:absolute;top:-50%;left:-20%;width:60%;height:200%;background:radial-gradient(circle,rgba(13,148,136,.3),transparent 70%);pointer-events:none}
.cta-box h2{font-size:2.8rem;color:#fff;margin-bottom:24px;position:relative;z-index:1}
.cta-box p{font-size:1.15rem;color:#94a3b8;max-width:600px;margin:0 auto 40px;line-height:1.7;position:relative;z-index:1}
.cta-box .btn{position:relative;z-index:1}

/* FOOTER */
footer{background:#0f172a;padding:80px 24px 40px;color:#fff}
.footer-main{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:64px}
.footer-logo{display:flex;align-items:center;gap:12px;text-decoration:none;margin-bottom:24px}
.footer-logo .logo-icon{width:40px;height:40px;background:linear-gradient(135deg,var(--teal),var(--teal-dark));border-radius:12px;display:flex;align-items:center;justify-content:center}
.footer-logo .logo-text{font-size:1.2rem;letter-spacing:-0.05em;font-weight:800;color:#fff;line-height:1}
.footer-logo .logo-sub{font-size:0.6rem;font-weight:800;color:rgba(255,255,255,0.5);letter-spacing:0.15em;text-transform:uppercase;margin-top:4px;line-height:1}
.footer-desc{color:#94a3b8;line-height:1.7;max-width:300px;margin-bottom:32px;font-size:0.95rem}
.footer-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(13,148,136,.15);border:1px solid rgba(13,148,136,.3);color:var(--teal-light);padding:8px 16px;border-radius:100px;font-size:0.85rem;font-weight:700}
.footer-col h5{font-size:0.85rem;text-transform:uppercase;letter-spacing:.1em;color:#64748b;margin-bottom:24px}
.footer-col ul{list-style:none;display:flex;flex-direction:column;gap:16px}
.footer-col a{color:#94a3b8;text-decoration:none;transition:.2s;display:inline-flex;align-items:center;font-size:0.95rem}
.footer-col a:hover{color:#fff;transform:translateX(4px)}
.footer-bottom{max-width:1200px;margin:0 auto;padding-top:32px;border-top:1px solid rgba(255,255,255,.05);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;color:#64748b;font-size:0.9rem}
.footer-legal{display:flex;gap:24px}
.footer-legal a{color:#64748b;text-decoration:none;transition:.2s}
.footer-legal a:hover{color:var(--teal)}

/* RESPONSIVE */
@media(max-width:1024px){
  .hero-container{grid-template-columns:1fr;text-align:center;gap:48px}
  .hero-content{max-width:100%;margin:0 auto}
  .hero-cta{justify-content:center}
  .hero-mockup{max-width:600px;margin:0 auto}
  .footer-main{grid-template-columns:1fr 1fr;gap:40px}
}
@media(max-width:768px){
  /* Navigation */
  .nav-toggle{display:flex}
  .nav-links{display:none;position:fixed;top:72px;left:0;right:0;bottom:0;background:rgba(240,253,250,.98);flex-direction:column;padding:32px 24px;gap:8px;overflow-y:auto}
  .nav-links.open{display:flex}
  .nav-links a.ghost{width:100%;text-align:center;padding:16px;font-size:1.1rem}
  .nav-links .btn{width:100%;margin-top:16px}
  
  /* Hero Section */
  .hero-section{padding:120px 20px 60px}
  .hero h1{font-size:2.5rem}
  .blob-1{width:300px;height:300px;top:-50px;left:-50px}
  .blob-2{width:200px;height:200px;top:50px;right:-50px}
  .hero-cta{flex-direction:column;gap:12px}
  .hero-cta .btn{width:100%}
  
  /* Sections & Typography */
  section{padding:60px 20px}
  .section-title{font-size:2.2rem}
  .section-sub{font-size:1rem}
  
  /* Grids */
  .features-grid{grid-template-columns:1fr}
  .steps-grid{grid-template-columns:1fr;gap:32px}
  
  /* Mockup adjustments for mobile */
  .m-stats{flex-direction:column;gap:12px}
  .mockup-body{padding:20px}
  .m-card{padding:20px}
  .m-amount{font-size:2rem}
  
  /* CTA Box */
  .cta-box{padding:48px 20px;border-radius:24px}
  .cta-box h2{font-size:1.8rem}
  .cta-box .btn{width:100%}
  
  /* Footer */
  .footer-main{grid-template-columns:1fr;gap:40px}
  .footer-bottom{flex-direction:column;text-align:center;gap:12px}
  .footer-legal{flex-wrap:wrap;justify-content:center}
}
@media(max-width:480px){
  .hero h1{font-size:2.1rem}
  .logo-text{font-size:1.1rem}
  .logo-icon{width:32px;height:32px}
  .btn{padding:12px 20px;font-size:0.95rem}
}
</style>
</head>
<body>

<!-- NAV -->
<nav id="navbar">
  <div class="nav-inner">
    <a href="/" class="logo">
      <div class="logo-icon">
        <svg width="24" height="24" class="text-white" fill="white" viewBox="0 0 24 24"><path d="M4 10v7h3v-7H4zm6 0v7h3v-7h-3zM2 22h19v-3H2v3zm14-12v7h3v-7h-3zm-4.5-9L2 6v2h19V6l-9.5-5z"/></svg>
      </div>
      <div class="logo-text-wrapper">
        <div class="logo-text">MoneyTrack</div>
        <div class="logo-sub">Precision Wealth</div>
      </div>
    </a>
    <div class="nav-links" id="navLinks">
      <a href="#features" class="ghost" onclick="toggleNav()">Fonctionnalités</a>
      <a href="#how" class="ghost" onclick="toggleNav()">Comment ça marche</a>
      <a href="{{ route('login') }}" class="ghost">Se connecter</a>
      <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Commencer</a>
    </div>
    <button class="nav-toggle" id="navToggle" aria-label="Menu" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- HERO -->
<header class="hero-section">
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="hero-container">
    <div class="hero-content">
      <div class="hero-badge anim-fadeup"><span></span> Gestion intelligente</div>
      <h1 class="hero anim-fadeup" style="margin-bottom:24px; font-size:3.5rem;">Maîtrisez vos finances avec <span class="accent">une clarté totale</span></h1>
      <p class="hero anim-fadeup-delay-1" style="font-size:1.15rem; color:var(--muted); line-height:1.7; margin-bottom:40px;">MoneyTrack est la plateforme web conçue pour suivre vos entrées, vos sorties et piloter votre budget en temps réel — simplement, précisément, en toute sécurité.</p>
      <div class="hero-cta anim-fadeup-delay-2">
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Accéder au tableau de bord</a>
        @else
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Créer un compte</a>
          <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Se connecter</a>
        @endauth
      </div>
    </div>
    <div class="hero-mockup anim-fadeup-delay-2">
      <div class="mockup-card">
        <div class="mockup-header">
          <div class="mockup-dot"></div><div class="mockup-dot"></div><div class="mockup-dot"></div>
        </div>
        <div class="mockup-body">
          <div class="m-card">
            <div class="m-title">Solde Global</div>
            <div class="m-amount">1 450 000 F</div>
          </div>
          <div class="m-stats">
            <div class="m-stat income">
              <div class="m-title">Entrées du mois</div>
              <div class="m-stat-val">+320 000 F</div>
            </div>
            <div class="m-stat expense">
              <div class="m-title">Sorties du mois</div>
              <div class="m-stat-val">-85 000 F</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- FEATURES -->
<section id="features">
  <div class="container">
    <span class="section-tag reveal">Ce que nous offrons</span>
    <h2 class="section-title reveal">Des outils taillés pour vos finances</h2>
    <p class="section-sub reveal">Pilotez votre budget avec des fonctionnalités pensées pour la simplicité, la précision et la sécurité de vos données.</p>
    
    <div class="features-grid">
      <div class="feat-card reveal">
        <div class="feat-icon"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div>
        <h3>Tableau de bord en temps réel</h3>
        <p>Visualisez en un clin d'œil vos revenus, dépenses et solde actuel à l'aide de graphiques clairs et interactifs.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></div>
        <h3>Catégories personnalisées</h3>
        <p>Organisez facilement vos transactions selon vos propres catégories de dépenses ou de revenus.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div>
        <h3>Exports PDF & Excel</h3>
        <p>Générez et téléchargez des rapports financiers professionnels pour vos comptables ou votre archivage.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></div>
        <h3>Gestion multi-utilisateurs</h3>
        <p>Attribuez des rôles (Administrateur, Caissier) avec des permissions strictes pour un contrôle absolu.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
        <h3>Sécurité & Traçabilité</h3>
        <p>Toutes les actions sont enregistrées dans un journal d'audit sécurisé et consultable par les administrateurs.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 8.485-7.5 11.625-7.5 11.625S5.25 14.86 5.25 6.375a7.5 7.5 0 0115 0z"/></svg></div>
        <h3>Sauvegarde complète</h3>
        <p>Téléchargez des sauvegardes ZIP de votre base de données et de vos fichiers à tout moment.</p>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section id="how" style="background:linear-gradient(180deg,#f0fdfa 0%,#fff 100%)">
  <div class="container">
    <span class="section-tag reveal">Simplicité d'utilisation</span>
    <h2 class="section-title reveal">Opérationnel en 3 étapes simples</h2>
    
    <div class="steps-grid" style="margin-top:64px;">
      <div class="step reveal">
        <div class="step-num">1</div>
        <h3>Configurez votre espace</h3>
        <p>Inscrivez-vous et définissez vos catégories de revenus et de dépenses selon vos besoins réels.</p>
      </div>
      <div class="step reveal">
        <div class="step-num">2</div>
        <h3>Enregistrez vos flux</h3>
        <p>Saisissez facilement vos transactions au quotidien via une interface épurée et intuitive.</p>
      </div>
      <div class="step reveal">
        <div class="step-num">3</div>
        <h3>Pilotez et décidez</h3>
        <p>Utilisez les rapports détaillés et le tableau de bord pour prendre de meilleures décisions financières.</p>
      </div>
    </div>
    
    <div class="cta-box reveal">
      <h2>Prêt à reprendre le contrôle de vos finances ?</h2>
      <p>Rejoignez MoneyTrack dès aujourd'hui et transformez la gestion de votre patrimoine financier. C'est rapide, sécurisé et pensé pour vous.</p>
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn-white btn-lg">Accéder à mon espace</a>
      @else
        <a href="{{ route('register') }}" class="btn btn-white btn-lg">Commencer gratuitement</a>
      @endauth
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-main">
    <div class="footer-col" style="grid-column: span 1;">
      <a href="/" class="footer-logo">
        <div class="logo-icon"><svg width="24" height="24" class="text-white" fill="white" viewBox="0 0 24 24"><path d="M4 10v7h3v-7H4zm6 0v7h3v-7h-3zM2 22h19v-3H2v3zm14-12v7h3v-7h-3zm-4.5-9L2 6v2h19V6l-9.5-5z"/></svg></div>
        <div class="logo-text-wrapper">
          <div class="logo-text">MoneyTrack</div>
          <div class="logo-sub" style="color: rgba(255,255,255,0.5);">Precision Wealth</div>
        </div>
      </a>
      <p class="footer-desc">La plateforme web conçue pour gérer, analyser et sécuriser vos flux financiers avec une simplicité déconcertante.</p>
      <div class="footer-badge">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
        Données sécurisées
      </div>
    </div>
    <div class="footer-col">
      <h5>Application</h5>
      <ul>
        <li><a href="{{ route('login') }}">Se connecter</a></li>
        <li><a href="{{ route('register') }}">Créer un compte</a></li>
        <li><a href="#features">Fonctionnalités</a></li>
        <li><a href="#how">Comment ça marche</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Ressources</h5>
      <ul>
        <li><a href="#">Tableau de bord</a></li>
        <li><a href="#">Export & Rapports</a></li>
        <li><a href="#">Sauvegarde des données</a></li>
        <li><a href="#">Journal d'audit</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Contact</h5>
      <ul>
        <li><a href="mailto:support@moneytrack.app">support@moneytrack.app</a></li>
        <li><a href="#">Documentation</a></li>
        <li><a href="#">Centre d'aide</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} MoneyTrack. Tous droits réservés.</p>
    <div class="footer-legal">
      <a href="#">Politique de confidentialité</a>
      <a href="#">Conditions d'utilisation</a>
    </div>
    <p>Fait avec <span style="color:#ef4444">❤</span> pour la finance</p>
  </div>
</footer>

<script>
  // Menu Mobile
  function toggleNav() {
    const links = document.getElementById('navLinks');
    const toggle = document.getElementById('navToggle');
    if (links.classList.contains('open')) {
      links.classList.remove('open');
      toggle.classList.remove('open');
      document.body.style.overflow = '';
    } else {
      links.classList.add('open');
      toggle.classList.add('open');
      document.body.style.overflow = 'hidden';
    }
  }

  // Scroll Reveal Animation
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
  };
  
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.reveal').forEach((el) => {
    observer.observe(el);
  });
</script>

</body>
</html>