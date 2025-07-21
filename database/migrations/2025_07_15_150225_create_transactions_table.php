<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_item_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 8, 2);
            $table->enum('method', ['cash', 'transfer', 'qris', 'lainnya']);
            $table->datetime('paid_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};