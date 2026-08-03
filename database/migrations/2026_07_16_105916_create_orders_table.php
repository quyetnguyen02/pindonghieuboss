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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->autoIncrement();

            $table->string('customer_name');

            $table->string('phone',20);

            $table->string('address');

            $table->decimal('total_price',15,0);

            $table->tinyInteger('status')->default(0);
            //0: chờ xác nhận
            //1: đã tạo đơn
            //2: đang giao
            //3: đang hoàn
            //4: đã hoàn
            //5: đã giao

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
