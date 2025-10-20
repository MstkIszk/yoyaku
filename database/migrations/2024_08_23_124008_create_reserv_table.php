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
        Schema::create('reserv', function (Blueprint $table) {
            $table->id();
            $table->integer('OrderNo')->comment('予約番号');
            $table->integer('Baseid')->comment('予約先店コード');
            $table->integer('Productid')->comment('予約商品');
            $table->string('KeyStr')->comment('照会時に比較する');	
            $table->dateTime('ReqDate')->comment('予約日時');	
            $table->dateTime('ReserveDate')->comment('予約日	');
            $table->integer('ClitNo')->comment('予約番号');	
            $table->string('ClitNameKanji')->comment('氏名漢字');	
            $table->string('ClitNameKana')->comment('氏名カナ');	
            $table->string('CliAddrZip')->comment('郵便番号');	
            $table->string('CliAddrPref')->comment('県名');	
            $table->string('CliAddrCity')->comment('市町村名');	
            $table->string('CliAddrOther')->comment('地区名');	
            $table->string('CliTel1')->comment('電話番号');	
            $table->string('CliEMail')->comment('メールアドレス');	
            $table->integer('CliResvType')->comment('予約タイプ');	
            $table->integer('CliResvCnt')->comment('予約人数');	
            $table->integer('CliWaysPay')->comment('支払い方法');	
            $table->Text('MessageText')->comment('連絡')->nullable();
            $table->integer('RandomNumber')->comment('確認用乱数')->nullable();
            $table->dateTime('UpdateDate')->comment('状態変更日時');
            $table->integer('Status')->comment('予約状態');	
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserv');
    }
};
