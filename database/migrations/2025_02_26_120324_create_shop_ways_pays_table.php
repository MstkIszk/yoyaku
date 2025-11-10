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
        // 支払方法テーブル
        Schema::create('shop_ways_pays', function (Blueprint $table) {
            $table->id();
            $table->integer('PrBit')->comment('bit割当');
            $table->string('PrName')->comment('支払方法');
            $table->timestamps();
        });
        // 予約タイプテーブル
        Schema::create('shop_reserv_typess', function (Blueprint $table) {
            $table->id();
            $table->integer('RtBit')->comment('bit割当');
            $table->string('RtName')->comment('予約タイプ名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_ways_pays');
        Schema::dropIfExists('shop_reserv_typess');
    }
};
