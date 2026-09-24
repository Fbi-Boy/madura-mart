<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice', 50)->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('sale_date');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'qris'])->default('cash');
            $table->enum('status', ['paid', 'cancelled'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['sale_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};