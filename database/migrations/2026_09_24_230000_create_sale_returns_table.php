<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 50)->unique();
            $table->foreignId('sale_id')->constrained('sales')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('return_date');
            $table->decimal('total', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'return_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};