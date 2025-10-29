<?php

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
        Schema::create('user_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baseCode')->comment('店舗ID');      // usersテーブルの id を参照
            $table->integer('productID')->comment('商品名コード');  //  表示の並び順を指定
            $table->string('productName')->comment('商品名');      //  対象となるサービス名など
            $table->date('DateStart')->comment('営業開始日');      //  営業期間の指定　開始
            $table->date('DateEnd')->comment('営業終了日');        //  　　　　　　　　終了
            $table->date('TimeStart')->comment('開始時刻');
            $table->date('TimeEnd')->comment('終了時刻'); 
            $table->integer('capacity')->comment('定員')->default(0);
            $table->integer('price')->comment('料金')->default(0);
            $table->integer('WaysPay')->comment('支払い方法Bit');
            $table->string('memo')->comment('メモ');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_products');
    }
};
