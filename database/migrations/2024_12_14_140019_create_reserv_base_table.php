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
        Schema::create('reserv_base', function (Blueprint $table) {
            $table->id();
            $table->string('spName')->comment('店舗名');
            $table->string('spCode')->comment('店舗コード');      //  URLで店舗を指定する場合のコード
            $table->string('spEigyoType')->comment('営業タイプ');  //  URLで店舗の中の営業タイプを指定する場合のコード
            $table->integer('spCapacity')->comment('定員');       //  定員
            $table->string('spNameKana')->comment('氏名カナ');	
            $table->string('spAddrZip')->comment('郵便番号');	
            $table->string('spAddrPref')->comment('県名');	
            $table->string('spAddrCity')->comment('市町村名');	
            $table->string('spAddrOther')->comment('地区名');	
            $table->string('spTel1')->comment('電話番号１');
            $table->string('spTel2')->comment('電話番号２')->default("");
            $table->string('spEMail')->comment('メールアドレス')->default("");
            $table->integer('spResvType')->comment('予約タイプ')->default(1);	
            $table->integer('spWaysPay')->comment('支払い方法')->default(1);	
            $table->Text('spMsgText')->comment('連絡')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserv_base');
    }
};
