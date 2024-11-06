<?php

use App\Http\Controllers\chatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReserveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::redirect('/', '/public');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//  投稿入力画面
Route::get('reserve/create/{ReqDate?}', [ReserveController::class,'create'])->name('reserve.create');
//  投稿の書き込み
Route::post('reserve/post', [ReserveController::class,'store'])->name('reserve.store');
//  投稿の確認
Route::post('reserve/confirm/{id?,keystr?}', [ReserveController::class,'confirm'])->name('reserve.confirm');
//  投稿の確認
Route::post('reserve/fixed/{id?,keystr?}', [ReserveController::class,'fixed'])->name('reserve.fixed');
//  投稿の一覧表示
Route::get('reserve/index', [ReserveController::class,'index'])->name('reserve.index');
//  投稿の表示
Route::get('reserve/show/{id}', [ReserveController::class,'show'])->name('reserve.show');
//  投稿の編集
Route::get('reserve/edit/{reserve}', [ReserveController::class,'edit'])->name('reserve.edit');
//  投稿の編集結果を書き込み
Route::patch('reserve/{reserve}', [ReserveController::class,'update'])->name('reserve.update');
//  投稿の削除
Route::delete('reserve/{reserve}', [ReserveController::class,'destroy'])->name('reserve.destroy');
//  カレンダー表示
Route::get('reserve/calender/{type?,month?}', [ReserveController::class,'calender'])->name('reserve.calender');
//  カレンダーデータ読み込み (BackEND)
Route::get('reserve/calenderGet/{month?}', [ReserveController::class,'calenderGet'])->name('reserve.calenderGet');
//  予約データ読み込み (BackEND)
Route::get('reserve/GetCustmerData/{tel?}', [ReserveController::class,'GetCustmerData'])->name('reserve.GetCustmerData');

//Route::post('chat/add', 'chatController@add')->name('add');
//Route::post('chat/add', [chatController::class,'add'])->name('chat.add');
//  一覧表示
//Route::get('chat/index', [chatController::class,'index'])->name('chat.index');

//Route::get('/chat/{recieve}' ,  [ChatController::class,'index'])->name('chat');
//Route::post('/chat/send' ,   [ChatController::class,'chatSend'])->name('chatSend');

//Route::get('/result/ajax', 'HomeController@getData');

require __DIR__.'/auth.php';
