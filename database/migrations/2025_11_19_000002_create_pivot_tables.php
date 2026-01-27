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
        Schema::create('dish_allergen', function (Blueprint $table) {
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('allergen_id');
            $table->primary(['dish_id', 'allergen_id']);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('allergen_id')->references('id')->on('allergens')->onDelete('cascade');
        });

        Schema::create('drink_allergen', function (Blueprint $table) {
            $table->unsignedBigInteger('drink_id');
            $table->unsignedBigInteger('allergen_id');
            $table->primary(['drink_id', 'allergen_id']);
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->foreign('allergen_id')->references('id')->on('allergens')->onDelete('cascade');
        });

        Schema::create('dish_menu', function (Blueprint $table) {
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('menu_id');
            $table->boolean('is_special')->default(false)->comment('Indica si el plato tiene precio especial independiente del menú');
            $table->decimal('custom_price', 8, 2)->nullable()->comment('Precio personalizado si is_special es true, sino usa el precio del menú');
            $table->primary(['dish_id', 'menu_id']);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });

        Schema::create('dish_order', function (Blueprint $table) {
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->primary(['dish_id', 'order_id']);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('drink_order', function (Blueprint $table) {
            $table->unsignedBigInteger('drink_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->primary(['drink_id', 'order_id']);
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drink_order');
        Schema::dropIfExists('dish_order');
        Schema::dropIfExists('dish_menu');
        Schema::dropIfExists('drink_allergen');
        Schema::dropIfExists('dish_allergen');
    }
};
