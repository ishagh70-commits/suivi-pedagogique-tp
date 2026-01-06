<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emplois', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('enseignant_id')->constrained('enseignants')->cascadeOnDelete();

            $table->string('matiere');
            $table->enum('jour', ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle')->nullable();

            $table->timestamps();

            // Optionnel mais utile : éviter doublons exacts
            $table->unique(['group_id','jour','heure_debut','heure_fin','enseignant_id'], 'emploi_unique_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emplois');
    }
};
