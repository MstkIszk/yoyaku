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
        Schema::create('shop_calenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dsShopId')->comment('店舗ID');       // userテーブルの id を参照
            $table->foreignId('dsProduct')->comment('商品コード');  //user_productsテーブルの id を参照
            $table->date('dsDate')->comment('対象日');
            $table->integer('dsMax')->comment('参加最大人数');
            $table->integer('dsCnt')->comment('実参加人数');
            $table->Text('dsMemo')->comment('連絡')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_calenders');
    }
};
