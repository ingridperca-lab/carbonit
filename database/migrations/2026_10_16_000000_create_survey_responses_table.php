<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at')->index();
            $table->string('status', 12);                      // complete, no_car, zero_days, not_student
            $table->boolean('in_stats')->default(false)->index(); // true = elev, intră în statistică
            $table->unsignedTinyInteger('days')->nullable();   // zile la școală (0-5)
            $table->unsignedTinyInteger('m_foot')->nullable();
            $table->unsignedTinyInteger('m_bike')->nullable();
            $table->unsignedTinyInteger('m_public')->nullable();
            $table->unsignedTinyInteger('m_car')->nullable();
            $table->unsignedTinyInteger('m_taxi')->nullable();
            $table->unsignedTinyInteger('m_other')->nullable();
            $table->string('dist', 12)->nullable();
            $table->string('traffic', 12)->nullable();
            $table->string('route', 300)->nullable();
            $table->string('why', 12)->nullable();
            $table->string('shared', 12)->nullable();
            $table->string('alt', 12)->nullable();
            $table->unsignedTinyInteger('replace_days')->nullable();
            $table->string('block_opts', 80)->nullable();
            $table->string('help_opts', 80)->nullable();
            $table->string('comment', 300)->nullable();
            $table->boolean('taxi_separate')->default(false);  // true = a folosit taxiul (analiză separată)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
