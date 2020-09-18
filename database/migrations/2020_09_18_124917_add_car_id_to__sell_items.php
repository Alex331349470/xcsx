<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarIdToSellItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sell_items', function (Blueprint $table) {
            $table->integer('car_id')->nullable()->after('id')->comment('车辆id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sell_items', function (Blueprint $table) {
            $table->dropColumn('car_id');
        });
    }
}
