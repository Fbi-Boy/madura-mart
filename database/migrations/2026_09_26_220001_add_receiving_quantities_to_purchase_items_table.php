<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_items', function (Blueprint $table): void {
            $table->unsignedInteger('received_quantity')->default(0)->after('quantity');
            $table->unsignedInteger('damaged_quantity')->default(0)->after('received_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table): void {
            $table->dropColumn(['received_quantity', 'damaged_quantity']);
        });
    }
};
