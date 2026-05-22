<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programmation de la sauvegarde automatique (exécutée tous les jours à minuit)
// Nécessite "php artisan schedule:run" activé sur le serveur (via tache cron / planificateur Windows)
Schedule::command('backup:run --only-db')->dailyAt('00:00');
