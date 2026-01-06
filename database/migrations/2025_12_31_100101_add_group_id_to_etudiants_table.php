<?php

// database/migrations/xxxx_add_group_id_to_etudiants_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('etudiants', function (Blueprint $table) {
      $table->foreignId('group_id')->nullable()->after('id')->constrained('groups')->nullOnDelete();
    });
  }

  public function down(): void {
    Schema::table('etudiants', function (Blueprint $table) {
      $table->dropForeign(['group_id']);
      $table->dropColumn('group_id');
    });
  }
};
