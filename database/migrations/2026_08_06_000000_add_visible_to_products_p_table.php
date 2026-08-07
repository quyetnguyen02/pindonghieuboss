<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products_p', function (Blueprint $table) {
            if (! Schema::hasColumn('products_p', 'visible')) {
                $table->boolean('visible')->default(1)->after('specifications');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products_p', function (Blueprint $table) {
            if (Schema::hasColumn('products_p', 'visible')) {
                $table->dropColumn('visible');
            }
        });
    }
};
