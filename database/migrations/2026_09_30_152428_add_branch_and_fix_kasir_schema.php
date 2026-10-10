<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ===== 1. TAMBAH branch_id KE users =====
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('id')
                    ->constrained('branches')->onDelete('set null');
            }
        });

        // ===== 2. TAMBAH branch_id + shift_type KE cashier_shifts =====
        Schema::table('cashier_shifts', function (Blueprint $table) {
            if (!Schema::hasColumn('cashier_shifts', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('user_id')
                    ->constrained('branches')->onDelete('set null');
            }
            if (!Schema::hasColumn('cashier_shifts', 'shift_type')) {
                $table->string('shift_type', 20)->default('pagi-siang')->after('branch_id');
            }
        });

        // ===== 3. TAMBAH branch_id KE sales =====
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('shift_id')
                    ->constrained('branches')->onDelete('set null');
            }
        });

        // ===== 4. FIX ENUM sales.payment_method (tambah 'debit') =====
        DB::statement("
            ALTER TABLE sales
            MODIFY COLUMN payment_method
            ENUM('cash','transfer','qris','debit') NOT NULL
        ");

        // ===== 5. FIX ENUM users.role =====
        // NOTE: kalau ada role lain di DB, sesuaikan list ini
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM('admin','super-admin','kasir','gudang','purchasing','kurir','customer') NOT NULL
        ");

        // ===== 6. FIX ENUM system_settings.type =====
        DB::statement("
            ALTER TABLE system_settings
            MODIFY COLUMN type
            ENUM('text','number','boolean','textarea','select','email','url','color','date') NOT NULL
        ");

        // ===== 7. UPDATE DATA EXISTING: isi branch_id default =====
        $defaultBranchId = DB::table('branches')->value('id');

        if ($defaultBranchId) {
            DB::table('users')->whereNull('branch_id')->update(['branch_id' => $defaultBranchId]);
            DB::table('sales')->whereNull('branch_id')->update(['branch_id' => $defaultBranchId]);
            DB::table('cashier_shifts')->whereNull('branch_id')->update(['branch_id' => $defaultBranchId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('cashier_shifts', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'shift_type']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        // Rollback enum
        DB::statement("ALTER TABLE sales MODIFY COLUMN payment_method ENUM('cash','transfer','qris') NOT NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE system_settings MODIFY COLUMN type VARCHAR(20) NOT NULL");
    }
};
