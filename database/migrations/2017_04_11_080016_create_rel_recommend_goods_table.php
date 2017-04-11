<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelRecommendGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_recommend_goods', function (Blueprint $table) {
            $table->increments('id')->comment('推荐位商品关联表');
            $table->integer('recommend_id')->comment('推荐位ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->timestamps();
            $table->softDeletes()->comment('软删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_recommend_goods');
    }
}
