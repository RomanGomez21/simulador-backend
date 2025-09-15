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
        Schema::create('period_structure_year', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_year_id')->constrained('period_year')->onDelete('restrict');
            $table->foreignId('structure_id')->constrained('structures')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_structure_year');
    }
};
