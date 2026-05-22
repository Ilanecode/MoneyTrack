<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class SettingsController extends Controller
{
    public function index()
    {
        $backupDir = 'backups';
        $backups   = [];

        // Créer le dossier de backup s'il n'existe pas
        if (!Storage::disk('local')->exists($backupDir)) {
            Storage::disk('local')->makeDirectory($backupDir);
        }

        $files = Storage::disk('local')->files($backupDir);
        foreach ($files as $file) {
            if (substr($file, -4) === '.zip') {
                $backups[] = [
                    'file_name'     => str_replace($backupDir . '/', '', $file),
                    'file_size'     => Storage::disk('local')->size($file),
                    'last_modified' => Storage::disk('local')->lastModified($file),
                ];
            }
        }

        usort($backups, function ($a, $b) {
            return $b['last_modified'] - $a['last_modified'];
        });

        $latestBackup  = !empty($backups) ? $backups[0] : null;
        $budgetMensuel = AppSetting::get('budget_mensuel', 1000000);

        return view('settings.index', compact('latestBackup', 'budgetMensuel'));
    }

    public function saveBudget(Request $request)
    {
        $request->validate([
            'budget_mensuel' => 'required|numeric|min:0',
        ]);

        AppSetting::set('budget_mensuel', $request->budget_mensuel, 'Budget mensuel de dépenses (en FCFA)');
        \App\Models\Audit::log('modification', 'Budget mensuel mis à jour : ' . number_format($request->budget_mensuel, 0, ',', ' ') . ' F');

        return back()->with('success', 'Budget mensuel mis à jour avec succès.');
    }

    /**
     * Crée une sauvegarde ZIP du fichier SQLite — sans dépendance externe.
     */
    public function backup()
    {
        try {
            $dbPath    = database_path('database.sqlite');
            $backupDir = storage_path('app/backups');

            if (!file_exists($dbPath)) {
                return back()->with('error', 'Fichier de base de données introuvable.');
            }

            if (!File::isDirectory($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $zipName = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = $backupDir . '/' . $zipName;

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return back()->with('error', 'Impossible de créer le fichier ZIP.');
            }
            $zip->addFile($dbPath, 'database.sqlite');
            $zip->close();

            \App\Models\Audit::log('système', 'Sauvegarde manuelle créée : ' . $zipName);

            return back()->with('success', 'Sauvegarde créée avec succès : ' . $zipName);

        } catch (\Exception $e) {
            return back()->with('error', 'Échec de la sauvegarde : ' . $e->getMessage());
        }
    }

    /**
     * Télécharge un fichier de sauvegarde.
     */
    public function downloadBackup($filename)
    {
        $path = 'backups/' . $filename;

        if (Storage::disk('local')->exists($path)) {
            \App\Models\Audit::log('système', 'Téléchargement de la sauvegarde : ' . $filename);
            return Storage::disk('local')->download($path);
        }

        return back()->with('error', 'Fichier introuvable.');
    }

    /**
     * Restaure la base de données depuis un .zip (contenant database.sqlite),
     * un fichier .sqlite direct, ou un script .sql compatible SQLite.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
        ]);

        $file      = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $dbPath    = database_path('database.sqlite');

        try {
            if ($extension === 'zip') {
                $zip     = new ZipArchive();
                $tempDir = storage_path('app/temp_restore_' . time());
                File::makeDirectory($tempDir, 0755, true);

                if ($zip->open($file->getRealPath()) !== true) {
                    File::deleteDirectory($tempDir);
                    return back()->with('error', 'Impossible d\'ouvrir le fichier ZIP.');
                }

                $zip->extractTo($tempDir);
                $zip->close();

                $allFiles   = File::allFiles($tempDir);
                $sqliteFile = null;
                $sqlFile    = null;

                foreach ($allFiles as $f) {
                    $ext = strtolower($f->getExtension());
                    if ($ext === 'sqlite' && !$sqliteFile) {
                        $sqliteFile = $f->getRealPath();
                    }
                    if ($ext === 'sql' && !$sqlFile) {
                        $sqlFile = $f->getRealPath();
                    }
                }

                if ($sqliteFile) {
                    // Restauration directe du fichier SQLite
                    DB::disconnect('sqlite');
                    File::copy($sqliteFile, $dbPath);
                    File::deleteDirectory($tempDir);
                    \App\Models\Audit::log('système', 'Restauration SQLite depuis ZIP réussie.');
                    return back()->with('success', 'Restauration effectuée avec succès !');
                }

                if ($sqlFile) {
                    // Restauration via script SQL filtré
                    DB::disconnect('sqlite');
                    $this->executeSqliteScript($sqlFile);
                    File::deleteDirectory($tempDir);
                    \App\Models\Audit::log('système', 'Restauration SQL depuis ZIP réussie.');
                    return back()->with('success', 'Restauration SQL effectuée avec succès !');
                }

                File::deleteDirectory($tempDir);
                return back()->with('error', 'Aucun fichier .sqlite ou .sql trouvé dans l\'archive ZIP.');

            } elseif ($extension === 'sqlite') {
                // Remplacement direct du fichier SQLite
                DB::disconnect('sqlite');
                $file->move(database_path(), 'database.sqlite');
                \App\Models\Audit::log('système', 'Restauration directe SQLite réussie.');
                return back()->with('success', 'Restauration effectuée avec succès !');

            } elseif ($extension === 'sql') {
                // Exécution du script SQL compatible SQLite
                DB::disconnect('sqlite');
                $this->executeSqliteScript($file->getRealPath());
                \App\Models\Audit::log('système', 'Restauration SQL réussie.');
                return back()->with('success', 'Restauration SQL effectuée avec succès !');

            } else {
                return back()->with('error', 'Format non supporté. Utilisez .zip, .sqlite ou .sql');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }

    /**
     * Exécute un script SQL en filtrant les instructions incompatibles avec SQLite.
     */
    private function executeSqliteScript(string $sqlPath): void
    {
        $sqlContent = file_get_contents($sqlPath);

        // Filtrer les instructions MySQL non compatibles SQLite
        $lines    = explode("\n", $sqlContent);
        $filtered = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/^(SET\s+(autocommit|unique_checks|foreign_key_checks|sql_mode|character_set|names)|\/\*!|LOCK\s+TABLES|UNLOCK\s+TABLES)/i', $trimmed)) {
                continue;
            }
            $filtered[] = $line;
        }

        $cleanSql   = implode("\n", $filtered);
        $statements = array_filter(
            array_map('trim', explode(';', $cleanSql)),
            fn ($s) => !empty($s)
        );

        DB::statement('PRAGMA foreign_keys = OFF');
        foreach ($statements as $statement) {
            DB::unprepared($statement . ';');
        }
        DB::statement('PRAGMA foreign_keys = ON');
    }
}
