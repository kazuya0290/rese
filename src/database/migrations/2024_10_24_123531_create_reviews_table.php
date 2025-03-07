<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->comment('1から5までの評価');
            $table->text('comment');
            $table->timestamps();
            $table->unique(['shop_id', 'user_id'], 'unique_shop_user_review');
            $table->string('image');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
