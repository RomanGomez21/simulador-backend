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
        Schema::create('energy_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_detail_id')->constrained('structure_details')->onDelete('restrict');
            $table->foreignId('energy_price_id')->constrained('energy_prices')->onDelete('restrict');
            $table->foreignId('ape_charge_id')->constrained('ape_charges')->onDelete('restrict');
            $table->string('description',45);
            $table->integer('min_range')->nullable();
            $table->integer('max_range')->nullable();
            $table->decimal('value',20,3);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_charges');
    }
};
