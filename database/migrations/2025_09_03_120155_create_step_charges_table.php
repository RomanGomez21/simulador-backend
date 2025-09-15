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
        Schema::create('step_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_detail_id')->constrained('structure_details')->onDelete('restrict');
            $table->string('description',45);
            $table->enum('unit',['energy','power'])->default('energy');
            $table->integer('min_range')->nullable(); //Se tomarán null los casos de Grandes Usuarios 
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
        Schema::dropIfExists('step_charges');
    }
};
