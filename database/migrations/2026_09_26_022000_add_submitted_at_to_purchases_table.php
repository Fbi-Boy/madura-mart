<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table): void {
            $table->timestamp('submitted_at')->nullable()->after('status');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table): void {
            $table->dropIndex(['submitted_at']);
            $table->dropColumn('submitted_at');
        });
    }
};
