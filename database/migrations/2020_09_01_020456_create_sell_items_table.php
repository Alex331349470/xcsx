<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_items', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->nullable()->comment('外键车辆id');
            $table->unsignedInteger('time')->default(0)->comment('套餐时间');
            $table->string('name')->comment('套餐名称');
            $table->decimal('price')->default(0)->comment('套餐金额');
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
        Schema::dropIfExists('sell_items');
    }
}
