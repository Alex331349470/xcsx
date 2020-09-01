<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index()->comment('外键用户id');
            $table->integer('driver_school_id')->index()->comment('外键驾校id');
            $table->decimal('total_account')->default(0)->comment('充值总金额');
            $table->decimal('left_money')->default(0)->comment('剩余金额');
            $table->boolean('is_vip')->default(false)->comment('是否为vip');
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
        Schema::dropIfExists('user_infos');
    }
}
