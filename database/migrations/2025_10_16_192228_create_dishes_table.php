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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            // ingredientes separados para conservar la información estructurada
            $table->text('ingredients')->nullable();
            // imagen: ruta relativa en storage (public disk)
            $table->string('image')->nullable();
            // precio y flags
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('available')->default(true);
            $table->boolean('special')->default(false);

            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('allergen_id')->constrained('allergens')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
