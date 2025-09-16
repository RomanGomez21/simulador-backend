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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('description',100);
            // Rango de energía
            $table->integer('min_range_energy')->nullable();
            $table->integer('max_range_energy')->nullable();
            $table->boolean('include_min_energy');
            $table->boolean('include_max_energy');
            // Rango de potencia
            $table->integer('min_range_power')->nullable();
            $table->integer('max_range_power')->nullable();
            $table->boolean('include_min_power');
            $table->boolean('include_max_power');
            // Potencia aparente del transformador
            $table->string('transformer_apparent_power',5)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
