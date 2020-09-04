<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_school_id')->index()->comment('外键驾校id');
            $table->string('serial_num')->nullable()->comment('车辆序列号');
            $table->string('name')->comment('车辆名称');
            $table->boolean('status')->default(false)->comment('车辆是否正在使用');
            $table->dateTime('start')->nullable()->comment('车辆使用开始时间');
            $table->dateTime('end')->nullable()->comment('车辆使用结束时间');
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
        Schema::dropIfExists('cars');
    }
}
