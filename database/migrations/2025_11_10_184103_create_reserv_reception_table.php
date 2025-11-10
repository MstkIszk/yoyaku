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
        Schema::create('reserv_reception', function (Blueprint $table) {
            $table->increments('id');               //  自動加算のID
            $table->foreignId('ReserveID')->comment('予約ID');      // reservテーブルの id を参照
			$table->integer('payType')->comment('料金タイプ');      // １:コース商品 / 2:オプション商品
			$table->integer('index')->comment('対象商品コード');    // 

            $table->integer('price')->comment('単価'); 	//  単価
            $table->integer('count')->comment('数量');     //  数量
            $table->string('memo')->comment('メモ');       //  メモ書き
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserv_reception');
    }
};
