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
        Schema::create('products', function (Blueprint $table) {
            $table->id()->autoIncrement();

            $table->string('name');

            $table->bigInteger('image_id');
            $table->bigInteger('category_id');

            // Giá niêm yết
            $table->unsignedBigInteger('original_price');

            // Giá khuyến mãi
            $table->unsignedBigInteger('sale_price');

            //0: phụ kiện
            //1: pin
            //2: điện
            $table->enum('type', ['0', '1', '2'])
                ->default('0')
                ->comment('kiểu sản phầm: phụ kiện or pin or điện');

            // Ví dụ: "[1,2,3,4]"
            $table->json('thumb_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
