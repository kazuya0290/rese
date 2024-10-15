<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('shop_id'); // お店に紐づく
        $table->string('name'); // お店の名前
        $table->text('description'); // お店の説明
        $table->string('image'); // お店の画像URL
        $table->timestamps();
        $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade'); // 外部キー制約
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
}
