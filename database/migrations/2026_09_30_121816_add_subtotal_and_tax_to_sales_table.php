<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->default(0)->after('sale_date');
            }
            if (!Schema::hasColumn('sales', 'tax')) {
                $table->decimal('tax', 15, 2)->default(0)->after('subtotal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax']);
        });
    }
};
