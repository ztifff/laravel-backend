<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class UpdateOrderStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    DB::statement("
        ALTER TABLE orders 
        MODIFY COLUMN status 
        ENUM('pending', 'paid', 'shipped', 'delivered', 'cancelled') 
        NOT NULL DEFAULT 'pending'
    ");
}

public function down(): void
{
    DB::statement("
        ALTER TABLE orders 
        MODIFY COLUMN status 
        ENUM('pending', 'processing', 'completed', 'cancelled') 
        NOT NULL DEFAULT 'pending'
    ");
}

}
