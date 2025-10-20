<?php
/*  
店舗登録テーブル
URL: http://127.0.0.1/yoyaku/public/register
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->default("");    // ロール "admin":店舗管理者、""一般ユーザー 
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('spName')->comment('店舗名');
            $table->string('spNameKana')->comment('店舗名カナ');	
            $table->string('spCode')->comment('店舗コード');      //  URLで店舗を指定する場合のコード
            $table->string('spAddrZip')->comment('郵便番号');	
            $table->string('spAddrPref')->comment('県名');	
            $table->string('spAddrCity')->comment('市町村名');	
            $table->string('spAddrOther')->comment('地区名');	
            $table->string('spTel1')->comment('電話番号１');
            $table->string('spTel2')->comment('電話番号２')->default("");
            $table->string('spEMail')->comment('メールアドレス')->default("");
            $table->string('spURL')->comment('URL')->default("");
            $table->integer('spResvType')->comment('予約タイプ')->default(1);	
            $table->Text('spMsgText')->comment('連絡')->default("");

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
