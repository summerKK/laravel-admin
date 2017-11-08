<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttributeSetLuxProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //        Schema::table('lux_products', function (Blueprint $table) {
        //            $table->tinyInteger('attribute_set')->change();
        //        });
        //Unknown column type "tinyinteger" requested
        //不支持tinyinteger
        DB::statement("ALTER TABLE lux_products CHANGE COLUMN attribute_set attribute_set TINYINT UNSIGNED NOT NULL comment 'lux_goods_type表的id 商品类型(包或者表)'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lux_products', function (Blueprint $table) {
            //
        });
    }
}
