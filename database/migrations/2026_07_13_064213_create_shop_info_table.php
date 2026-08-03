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
        Schema::create('shop_info', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('shop_name');          // Tên shop
            $table->string('logo')->nullable();   // Logo
            $table->string('address')->nullable();            // Địa chỉ
            $table->string('hotline', 20)->nullable();        // Hotline
            $table->string('zalo', 20)->nullable();        // Zalo
            $table->string('email')->nullable();  // Email
            $table->string('fanpage')->nullable(); // Link Fanpage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_info');
    }
};
