<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – MoneyTrack</title>
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
        .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 18px;
            height: 18px;
        }
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
        .register-text {
            text-align: center;
            margin-top: 12px;
            font-size: 0.83rem;
            color: #6b7280;
        }
        .register-text a {
            color: #0d9488;
            font-weight: 600;
            text-decoration: none;
        }
        .register-text a:hover { text-decoration: underline; }
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
        <h1 class="card-title">Bienvenue</h1>
        <p class="card-subtitle">Suivez vos actifs avec une clarté cristalline.</p>

        <!-- Formulaire -->
        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- Identifiant -->
            <div class="form-group">
                <label for="identifiant">Nom d'utilisateur ou Email</label>
                <div class="input-wrapper">
                    <input
                        type="text"
                        id="identifiant"
                        name="identifiant"
                        placeholder="admin ou admin12@gmail.com"
                        value="{{ old('identifiant', 'admin') }}"
                        required
                        autofocus
                    >
                </div>
                @error('identifiant') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Mot de Passe</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        value="admin123"
                        required
                    >
                    <svg class="input-icon" id="togglePasswordIcon" style="cursor: pointer;" onclick="togglePasswordVisibility()" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <!-- Bouton -->
            <button type="submit" class="btn-login">
                Se Connecter
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>
        </form>

        <!-- Liens -->
        <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
        <a href="{{ route('register') }}" class="forgot-link" style="margin-top: 10px;">S'inscrire</a>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>
</html>
