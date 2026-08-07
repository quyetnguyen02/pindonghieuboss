<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products_p', function (Blueprint $table) {
            if (! Schema::hasColumn('products_p', 'cell_type')) {
                $table->unsignedBigInteger('cell_type')->nullable()->after('type');
            }
            if (! Schema::hasColumn('products_p', 'cell_number')) {
                $table->unsignedInteger('cell_number')->nullable()->after('cell_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products_p', function (Blueprint $table) {
            if (Schema::hasColumn('products_p', 'cell_number')) {
                $table->dropColumn('cell_number');
            }
            if (Schema::hasColumn('products_p', 'cell_type')) {
                $table->dropColumn('cell_type');
            }
        });
    }
};
