<?php
/*  
サービス登録テーブル
登録画面    app\Http\Controllers\Auth\RegisteredUserController.php
           → resources\views\auth\register.blade.php



登録画面           app\Http\Controllers\ProfileController.php   
edit()             resources\views\profile\edit.blade.php 
データ登録(post)：  app\Http\Controllers\Auth\RegisteredUserController.php
*/
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
        //  メイン商品
        Schema::create('user_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baseCode')->comment('店舗ID');      // usersテーブルの id を参照
            $table->foreignId('productID')->comment('商品名コード');  //  表示の並び順を指定
            $table->string('productName')->comment('商品名');      //  対象となるサービス名など
            $table->integer('IsEnabled')->comment('有効/無効')->default(1);       //  表示対象とするかを指定
            $table->date('DateStart')->comment('営業開始日');      //  営業期間の指定　開始
            $table->date('DateEnd')->comment('営業終了日');        //  　　　　　　　　終了
            $table->time('TimeStart')->comment('開始時刻');
            $table->time('TimeEnd')->comment('終了時刻'); 
            $table->integer('capacity')->comment('定員')->default(0);
            $table->integer('WeekdayPrice')->comment('平日料金')->default(0);
            $table->integer('WeekendPrice')->comment('休日料金')->default(0);
            $table->string('AddtionalName')->comment('その他料金名')->default("");
            $table->integer('AddtionalPrice')->comment('その他料金')->default(0);
            $table->integer('ResvTypeBit')->comment('予約タイプBit');   // shop_reserv_typessテーブルのRtBitを参照
            $table->integer('WaysPayBit')->comment('支払い方法Bit');   // shop_ways_paysテーブルのPrBitを参照   
            $table->string('memo')->comment('メモ');

            $table->timestamps();
        });

        //  商品コース
        Schema::create('user_course', function (Blueprint $table) {
            $table->increments('id');                //  自動加算のID
            $table->foreignId('baseCode')->comment('店舗ID');      // usersテーブルの id を参照
            $table->foreignId('productID')->comment('商品名ID');  //  user_productsテーブルの id を参照
            $table->integer('courseCode')->comment('コースコード');  //  コースコード表示順
            $table->string('courseName')->comment('コース名');       //  コース名
            $table->integer('IsEnabled')->comment('有効/無効')->default(1);       //  表示対象とするかを指定
            $table->integer('weekdayPrice')->comment('平日料金')->default(0);
            $table->integer('weekendPrice')->comment('休日料金')->default(0);
            $table->string('memo')->comment('メモ');             //  メモ書き
            $table->timestamps();
        });

        //  オプション商品
        Schema::create('user_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baseCode')->comment('店舗ID');      // usersテーブルの id を参照
            $table->foreignId('productID')->comment('商品名コード');  //  表示の並び順を指定
            $table->string('productName')->comment('商品名');      //  商品名
            $table->integer('IsEnabled')->comment('有効/無効')->default(1);       //  表示対象とするかを指定
            $table->string('memo')->comment('メモ');
            $table->integer('price')->comment('料金')->default(0);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accessories');
        Schema::dropIfExists('user_course');
        Schema::dropIfExists('user_products');
    }
};
