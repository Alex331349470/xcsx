<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->index()->comment('外键车辆id');
            $table->integer('driver_school_id')->index()->comment('外键驾校id');
            $table->integer('sell_item_id')->index()->nullable()->comment('外键套餐id');
            $table->string('no')->nullable()->comment('订单号');
            $table->mediumInteger('left_time')->default(0)->comment('剩余时间/秒');
            $table->decimal('income')->default(0)->comment('收入');
            $table->dateTime('paid_at')->nullable()->comment('支付日期');
            $table->string('payment_num')->nullable()->comment('支付流水号');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
