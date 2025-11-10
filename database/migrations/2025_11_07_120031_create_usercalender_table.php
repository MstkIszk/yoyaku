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
        Schema::create('user_calenders', function (Blueprint $table) {
            $table->increments('id');       // 自動加算のID
            $table->dateTime('destDate')->comment('対象日付');      // 対象日付
            $table->integer('datetype')->comment('日付タイプ');     // 日付タイプ (1:土曜, 2:日曜, 3:祭日)
            $table->string('memo')->nullable()->comment('メモ');    // メモ書き
            $table->timestamps();

            // 対象日付と店舗IDの組み合わせで重複を防ぐためのユニークインデックスを追加
            $table->unique(['destDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_calenders');
    }
};
