<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat_supplier', function (Blueprint $table) {
            $table->foreignId('obat_id')->constrained('obats')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['obat_id', 'supplier_id']);
        });

        if (Schema::hasColumn('obats', 'supplier_id')) {
            $rows = DB::table('obats')
                ->whereNotNull('supplier_id')
                ->get(['id', 'supplier_id']);

            $inserts = [];
            foreach ($rows as $row) {
                $inserts[] = [
                    'obat_id' => $row->id,
                    'supplier_id' => $row->supplier_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($inserts) > 0) {
                DB::table('obat_supplier')->insertOrIgnore($inserts);
            }

            Schema::table('obats', function (Blueprint $table) {
                $table->dropConstrainedForeignId('supplier_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('obats', 'supplier_id')) {
            Schema::table('obats', function (Blueprint $table) {
                $table->foreignId('supplier_id')
                    ->nullable()
                    ->after('relasional_obat_id')
                    ->constrained('suppliers')
                    ->nullOnDelete();
            });
        }

        if (Schema::hasTable('obat_supplier')) {
            $rows = DB::table('obat_supplier')
                ->orderBy('supplier_id')
                ->get(['obat_id', 'supplier_id']);

            foreach ($rows as $row) {
                DB::table('obats')
                    ->where('id', $row->obat_id)
                    ->whereNull('supplier_id')
                    ->update(['supplier_id' => $row->supplier_id]);
            }

            Schema::dropIfExists('obat_supplier');
        }
    }
};
