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
            $table->integer('baseCode')->comment('店舗コード');     //  対象の店舗コード
            $table->integer('productID')->comment('商品名コード');      //  営業タイプ
            $table->string('productName')->comment('商品名');      //  営業タイプ
            $table->date('DateStart')->comment('営業開始日');   //  対象日付
            $table->date('DateEnd')->comment('営業終了日');     //  対象日付
            $table->date('TimeStart')->comment('開始時刻');   //  対象日付
            $table->date('TimeEnd')->comment('終了時刻'); 
            $table->integer('capacity')->comment('定員');
            $table->integer('price')->comment('料金');
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
