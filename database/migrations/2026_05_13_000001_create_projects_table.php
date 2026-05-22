<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_contact')->nullable();
            $table->text('description')->nullable();
            $table->decimal('budget', 15, 2);
            $table->enum('modalite_paiement', ['cash', 'fraction'])->default('cash');
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->string('statut')->default('en_cours'); // en_cours, solde, annule
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
