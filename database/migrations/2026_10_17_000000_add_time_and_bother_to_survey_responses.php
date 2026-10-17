<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->string('time_to_school', 12)->nullable()->after('days'); // sub15, 15-20, 20-30, peste30
            $table->string('bother', 300)->nullable()->after('route');       // ce îl deranjează dimineața
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['time_to_school', 'bother']);
        });
    }
};
