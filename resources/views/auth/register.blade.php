<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription – MoneyTrack</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230f766e'/%3E%3Crect x='4' y='18' width='5' height='10' rx='1.5' fill='white'/%3E%3Crect x='11' y='12' width='5' height='16' rx='1.5' fill='white' opacity='.85'/%3E%3Crect x='18' y='7' width='5' height='21' rx='1.5' fill='white'/%3E%3Crect x='25' y='14' width='3' height='14' rx='1.5' fill='white' opacity='.7'/%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 50%, #0e7490 100%);
            padding: 20px;
        }
        .login-card {
            background: #f0fafa;
            border-radius: 20px;
            padding: 48px 44px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.18);
        }
        .logo {
            text-align: center;
            font-size: 1.6rem;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }
        .logo span:first-child { font-weight: 400; color: #0f766e; }
        .logo span:last-child  { font-weight: 800; color: #0a0a0a; }
        .card-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }
        .card-subtitle {
            text-align: center;
            font-size: 0.82rem;
            color: #6b7280;
            margin-bottom: 32px;
        }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper input {
            width: 100%;
            padding: 13px 44px 13px 16px;
            border: none;
            border-radius: 10px;
            background: #e8f4f4;
            font-size: 0.9rem;
            color: #374151;
            outline: none;
            transition: box-shadow 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .input-wrapper input::placeholder { color: #9ca3af; }
        .input-wrapper input:focus { box-shadow: 0 0 0 3px rgba(13,148,136,0.25); }
        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: #0d9488;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 24px;
            transition: background 0.2s, transform 0.15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-login:hover { background: #0f766e; transform: translateY(-1px); }
        .btn-login:active { transform: scale(0.98); }
        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 0.83rem;
            color: #0d9488;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }
        .error-msg {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 4px;
            display: block;
        }
        
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo -->
        <div class="logo">
            <span>Money</span><span>Track</span>
        </div>

        <!-- Titre -->
        <h1 class="card-title">Créer un compte</h1>
        <p class="card-subtitle">Rejoignez l'écosystème MoneyTrack.</p>

        <!-- Formulaire -->
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Nom -->
            <div class="form-group">
                <label for="name">Nom Complet</label>
                <div class="input-wrapper">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Dagrou ilane"
                        value="{{ old('name') }}"
                        required
                        autofocus
                    >
                </div>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nom123@gmail.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                </div>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <!-- Confirmation Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="••••••••"
                        required
                    >
                </div>
            </div>

            <!-- Bouton -->
            <button type="submit" class="btn-login">
                S'inscrire
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </form>

        <!-- Liens -->
        <a href="{{ route('login') }}" class="forgot-link"><span class="deja">Déjà un compte ?</span> Se connecter</a>
    </div>
</body>
</html>
