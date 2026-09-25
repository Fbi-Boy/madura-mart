<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['bank_transfer', 'qris'])->nullable()->after('total');
            $table->enum('payment_status', ['pending', 'paid', 'rejected'])->default('pending')->after('payment_method');
            $table->string('payment_proof')->nullable()->after('payment_status');
            $table->index(['payment_status', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'status']);
            $table->dropColumn(['payment_method', 'payment_status', 'payment_proof']);
        });
    }
};
