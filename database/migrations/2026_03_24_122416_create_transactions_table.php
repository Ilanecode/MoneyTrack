<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['entrée', 'sortie']);
            $table->decimal('montant', 12, 2);
            $table->string('libelle');
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('mode_paiement')->default('cash'); // cash, mobile_money, virement
            $table->string('source')->nullable();       // pour entrées : vente, contribution...
            $table->string('beneficiaire')->nullable(); // pour sorties
            $table->text('description')->nullable();
            $table->date('date');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
