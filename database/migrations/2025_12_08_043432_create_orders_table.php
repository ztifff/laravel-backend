<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // User owning the order
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Total amount
            $table->decimal('total', 10, 2);

            // Status
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                  ->default('pending');

            // FIX: use text instead of string
            $table->text('shipping_address');

            // Payment method
            $table->string('payment_method');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
