<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création de l'administrateur par défaut
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@moneytrack.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Création d'un caissier par défaut
        User::create([
            'name' => 'Caissier',
            'email' => 'caissier@moneytrack.com',
            'password' => Hash::make('caissier123'),
            'role' => 'caissier',
        ]);

        // Catégories d'entrées
        $entrees = ['Vente', 'Contribution', 'Remboursement', 'Virement interne'];
        foreach ($entrees as $nom) {
            Categorie::create(['nom' => $nom, 'type' => 'entrée']);
        }

        // Catégories de sorties
        $sorties = ['Carburant', 'Salaire', 'Fournitures', 'Maintenance', 'Loyer', 'Électricité'];
        foreach ($sorties as $nom) {
            Categorie::create(['nom' => $nom, 'type' => 'sortie']);
        }
    }
}
