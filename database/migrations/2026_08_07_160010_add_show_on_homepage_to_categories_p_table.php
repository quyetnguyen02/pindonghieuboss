<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories_p', function (Blueprint $table) {
            if (! Schema::hasColumn('categories_p', 'show_on_homepage')) {
                $table->boolean('show_on_homepage')->default(false)->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories_p', function (Blueprint $table) {
            if (Schema::hasColumn('categories_p', 'show_on_homepage')) {
                $table->dropColumn('show_on_homepage');
            }
        });
    }
};
