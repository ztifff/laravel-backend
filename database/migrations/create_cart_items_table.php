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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Link to the User (The person viewing "Your Cart")
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Link to the Product (To get the Image, Name, and Price shown in the screenshot)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Stores the number in the quantity box (e.g., the "1" between the - and + buttons)
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};