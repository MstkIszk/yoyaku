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
        Schema::create('reserv_product', function (Blueprint $table) {
            $table->id();
            $table->string('Baseid')->comment('店舗コード');    //  reservBaseTableとのリンク
            $table->string('Productid')->comment('商品コード');    //  reservBaseTableとのリンク
            $table->string('spProductName')->comment('商品名');
            $table->string('spPrise')->comment('価格');
            $table->integer('spCapacity')->comment('定員');
            $table->integer('spResvType')->comment('予約タイプ')->default(1);	
            $table->integer('spWaysPay')->comment('支払い方法')->default(1);	
            $table->Text('spMsgText')->comment('連絡');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserv_product');
    }
};
