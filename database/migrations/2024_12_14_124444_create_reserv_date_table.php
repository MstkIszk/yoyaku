<?php
// 営業日テーブル 
// 店舗とサービスについて日付毎の予約可能情報を登録するテーブル
//  Model:

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
        Schema::create('reserv_date', function (Blueprint $table) {
            $table->increments('id');                //  自動加算のID
            $table->foreignId('baseCode')->comment('店舗ID');       // usersテーブルの id を参照
            $table->foreignId('productID')->comment('商品名ID');    //  user_productsテーブルの id を参照
            $table->integer('eigyotype')->comment('営業タイプ');    //  営業タイプ

            $table->dateTime('destDate')->comment('対象日付');      //  対象日付
            $table->integer('operating')->comment('営業状態');      // 1:通常営業・2:休業・3:貸し切り
            $table->integer('capacity')->comment('定員');           //  定員
            $table->integer('yoyakusu')->comment('予約人数');       //  ネット予約以外の予約人数
            $table->integer('stars')->comment('釣果レベル')->default(0);        //  指定日の出来高 ★１～５
            
            $table->string('memo')->comment('メモ');                //  メモ書き

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserv_date');
    }
};
