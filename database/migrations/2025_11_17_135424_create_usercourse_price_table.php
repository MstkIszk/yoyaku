<?php
//
//  予約コース料金テーブル定義
//  Model : UserCoursePrice

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_course_price', function (Blueprint $table) {
            $table->increments('id');                //  自動加算のID
            $table->foreignId('baseCode')->comment('店舗ID');      // usersテーブルの id を参照
            $table->foreignId('productID')->comment('商品名ID');  //  user_productsテーブルの id を参照
            $table->integer('courseCode')->comment('コースコード');  //  コースコード表示順
            $table->integer('priceCode')->comment('料金コード');  //  料金コード表示順
            $table->string('priceName')->comment('料金名');       //  料金名
            $table->integer('IsEnabled')->comment('有効/無効')->default(1);       //  表示対象とするかを指定
            $table->integer('weekdayPrice')->comment('平日料金')->default(0);
            $table->integer('weekendPrice')->comment('休日料金')->default(0);
            $table->string('memo')->comment('メモ');             //  メモ書き
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_course_price');
    }
};
