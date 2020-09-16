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
            $table->integer('sell_item_id')->index()->nullable()->comment('外键套餐id');
            $table->string('no')->nullable()->comment('订单号');
            $table->mediumInteger('left_time')->default(0)->comment('剩余时间/秒');
            $table->decimal('income')->default(0)->comment('收入');
            $table->dateTime('paid_at')->nullable()->comment('支付日期');
            $table->string('payment_no')->nullable()->comment('支付流水号');
            $table->tinyInteger('status')->default(0)->comment('订单状态');
            $table->string('pay_man')->nullable()->comment('付款人');
            $table->boolean('refund')->default(false)->comment('退款状态');
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
