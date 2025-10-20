<?php

use App\Http\Controllers\chatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\ReserveDateController;
use App\Http\Controllers\ReserveBaseController;
use App\Http\Controllers\ShopClosedModalController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\UserProductController;

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

/*Route::get('/', function () {
//    return view('welcome');
//});*/

Route::get('/', [ProfileController::class, 'homelist']);
Route::get('/home', [ProfileController::class, 'homelist'])->name('profile.homelist');

Route::get('reserve/create/{ReqDate?}', [ReserveController::class,'create'])->name('reserve.create');

Route::get('/dashboard', function () {
    return view('dashboard');   //  resources\views\dashboard.blade.php
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('userinfedit', [ProfileController::class, 'userinfedit'])->name('userinf.edit');
    Route::put('userinfupdate', [ProfileController::class, 'userinfupdate'])->name('userinf.update');
});

//------------------------------------------------------------
//◆◆◆◆◆◆◆◆◆◆◆  店舗別画面  ◆◆◆◆◆◆◆◆◆◆◆◆◆◆
//------------------------------------------------------------
//  店舗登録画面
Route::get('reservebase/create', [ReserveBaseController::class,'create'])->name('reservebase.create');
//  店舗の書き込み
Route::post('reservebase/post', [ReserveBaseController::class,'store'])->name('reservbae.store');
//  店舗の編集
Route::get('reservebase/edit/{ReserveBase}', [ReserveBaseController::class,'edit'])->name('reservebase.edit');
//  商品の登録
Route::get('/user_products/create', [UserProductController::class, 'create'])->name('user_products.create');

Route::post('/user_products', [UserProductController::class, 'store'])->name('user_products.store');

//------------------------------------------------------------
//◆◆◆◆◆◆◆◆◆◆◆  予約登録関係  ◆◆◆◆◆◆◆◆◆◆◆◆◆◆
//------------------------------------------------------------
//  予約入力画面
Route::get('reserve/create/{ReqDate?}', [ReserveController::class,'create'])->name('reserve.create');
//  予約の書き込み
Route::post('reserve/post', [ReserveController::class,'store'])->name('reserve.store');
//  予約の確認
Route::post('reserve/confirm/{id?,keystr?}', [ReserveController::class,'confirm'])->name('reserve.confirm');
//  予約の確認
Route::post('reserve/fixed/{id?,keystr?}', [ReserveController::class,'fixed'])->name('reserve.fixed');
//  電話番号による予約検索画面を表示
Route::get('reserve/telnoinput', [ReserveController::class,'telnoinput'])->name('reserve.telnoinput');
//  予約の一覧表示
Route::get('reserve/index/{CliTel1?}', [ReserveController::class,'index'])->name('reserve.index');
//  予約の表示
Route::get('reserve/show/{id}', [ReserveController::class,'show'])->name('reserve.show');
//  予約の編集
Route::get('reserve/edit/{reserve}', [ReserveController::class,'edit'])->name('reserve.edit');
//  予約の編集結果を書き込み
Route::patch('reserve/{reserve}', [ReserveController::class,'update'])->name('reserve.update');
//  予約の削除
Route::delete('reserve/{reserve}', [ReserveController::class,'destroy'])->name('reserve.destroy');

//  指定日情報の読み込み (BackEND)
Route::get('reserve/readDateInfo/{ReqDate?}', [ReserveDateController::class,'readDateInfo'])->name('reserve.readDateInfo');
//  指定日情報の書き込み (BackEND)
Route::post('reserve/writeDateInfo/{status?}', [ReserveDateController::class,'writeDateInfo'])->name('reserve.writeDateInfo');




//  店舗と予約情報を表示
Route::get('reserve/shopsel/{id}', [ReserveController::class,'shopsel'])->name('reserve.shopsel');
//  予約カレンダー表示
Route::get('reserve/calender/{month?,user?}', [ReserveController::class,'calender'])->name('reserve.calender');
//  カレンダーデータ読み込み (BackEND)
Route::get('reserve/calenderGet/{id?,month?}', [ReserveController::class,'calenderGet'])->name('reserve.calenderGet');
//  月間予約データ読み込み (BackEND)
Route::get('reserve/GetCustmerData/{tel?}', [ReserveController::class,'GetCustmerData'])->name('reserve.GetCustmerData');


Route::get('reserve/modal/{distdate?}', [ShopClosedModalController::class, 'modal'])->name('livewire.shop-closed-modal');

Route::get('/modal', [ModalController::class, 'modal']);

//Route::post('chat/add', 'chatController@add')->name('add');
//Route::post('chat/add', [chatController::class,'add'])->name('chat.add');
//  一覧表示
//Route::get('chat/index', [chatController::class,'index'])->name('chat.index');

//Route::get('/chat/{recieve}' ,  [ChatController::class,'index'])->name('chat');
//Route::post('/chat/send' ,   [ChatController::class,'chatSend'])->name('chatSend');

//Route::get('/result/ajax', 'HomeController@getData');

require __DIR__.'/auth.php';
