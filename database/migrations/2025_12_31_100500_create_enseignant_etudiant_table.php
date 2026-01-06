<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('enseignant_etudiant', function (Blueprint $table) {
      $table->id();
      $table->foreignId('enseignant_id')->constrained('enseignants')->cascadeOnDelete();
      $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['enseignant_id', 'etudiant_id']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('enseignant_etudiant');
  }
};

