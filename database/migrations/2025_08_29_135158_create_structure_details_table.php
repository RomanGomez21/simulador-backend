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
        Schema::create('structure_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained('structures')->onDelete('restrict');
            $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_details');
    }
};
