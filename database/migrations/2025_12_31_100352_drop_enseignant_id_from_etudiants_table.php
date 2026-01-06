<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('etudiants', function (Blueprint $table) {
      // adapte le nom si ta colonne s'appelle autrement
      if (Schema::hasColumn('etudiants', 'enseignant_id')) {
        $table->dropForeign(['enseignant_id']);
        $table->dropColumn('enseignant_id');
      }
    });
  }

  public function down(): void {
    Schema::table('etudiants', function (Blueprint $table) {
      $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->nullOnDelete();
    });
  }
};

