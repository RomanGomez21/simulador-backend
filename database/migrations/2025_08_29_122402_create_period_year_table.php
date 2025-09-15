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
        Schema::create('period_year', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->onDelete('restrict');
            $table->foreignId('year_id')->constrained('years')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_year');
    }
};
