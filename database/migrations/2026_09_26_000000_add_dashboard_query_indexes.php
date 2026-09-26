<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEXES = [
        'purchases' => [
            'purchases_status_purchase_date_index' => ['status', 'purchase_date'],
        ],
        'sales' => [
            'sales_status_sale_date_index' => ['status', 'sale_date'],
        ],
        'orders' => [
            'orders_status_order_date_index' => ['status', 'order_date'],
        ],
        'products' => [
            'products_active_stock_index' => ['is_active', 'stock'],
        ],
        'suppliers' => [
            'suppliers_active_index' => ['is_active'],
        ],
    ];

    public function up(): void
    {
        foreach (self::INDEXES as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $existingIndexes = collect(Schema::getIndexes($table))
                ->pluck('name')
                ->all();

            Schema::table($table, function (Blueprint $blueprint) use ($indexes, $existingIndexes): void {
                foreach ($indexes as $indexName => $columns) {
                    if (! in_array($indexName, $existingIndexes, true)) {
                        $blueprint->index($columns, $indexName);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        foreach (self::INDEXES as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $existingIndexes = collect(Schema::getIndexes($table))
                ->pluck('name')
                ->all();

            Schema::table($table, function (Blueprint $blueprint) use ($indexes, $existingIndexes): void {
                foreach (array_keys($indexes) as $indexName) {
                    if (in_array($indexName, $existingIndexes, true)) {
                        $blueprint->dropIndex($indexName);
                    }
                }
            });
        }
    }
};
