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
use App\Http\Controllers\ReserveReceptionController;
use App\Http\Controllers\UserAccessoryController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\UserCoursePriceController;
use App\Http\Controllers\DashboardController;

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

// 店舗を選択して店舗情報を表示
Route::get('/shopsel/{shop_id}', [ProfileController::class, 'shopsel'])->name('profile.shopsel');



Route::get('reserve/create/{user_id}/{product_id}/{ReqDate}', [ReserveController::class,'create'])->name('reserve.create');

//Route::get('/dashboard', function () {
//    return view('dashboard');   //  resources\views\dashboard.blade.php
//})->middleware(['auth', 'verified'])->name('dashboard');

// コントローラを呼び出すように修正 (推奨)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('userinfedit', [ProfileController::class, 'userinfedit'])->name('userinf.edit');
    Route::patch('userinfupdate', [ProfileController::class, 'userinfupdate'])->name('userinf.update');

    // 店舗別予約 View表示の初期画面ロード
    Route::get('/reservelist', [ProfileController::class, 'reserveList'])->name('userinf.rindex');
});

//------------------------------------------------------------
//◆◆◆◆◆◆◆◆◆◆◆  店舗別画面  ◆◆◆◆◆◆◆◆◆◆◆◆◆◆
//------------------------------------------------------------
Route::middleware('auth')->group(function () {
        //  店舗登録画面
    Route::get('reservebase/create', [ReserveBaseController::class,'create'])->name('reservebase.create');
    //  店舗の書き込み
    Route::post('reservebase/post', [ReserveBaseController::class,'store'])->name('reservbae.store');
    //  店舗の編集
    Route::get('reservebase/edit/{ReserveBase}', [ReserveBaseController::class,'edit'])->name('reservebase.edit');
    //  商品の登録
    Route::get('/user_products/create', [UserProductController::class, 'create'])->name('user_products.create');

    //  店舗カレンダー
    //Route::get('/shopcalender', [ReserveReceptionController::class, 'create'])->name('user_products.create');

    //  日付別予約表示
    Route::get('/dayinfo/{reqdate?}', [ReserveReceptionController::class, 'index'])->name('ReserveReception.index');
    Route::post('/api/reservations/list', [ReserveReceptionController::class, 'getReservationsForProduct'])->name('api.reservations.list');
    //  予約の受付画面
    Route::get('/reception/{reservid?}', [ReserveReceptionController::class, 'create'])->name('ReserveReception.create');
    Route::post('/reception', [ReserveReceptionController::class, 'store'])->name('ReserveReception.store');
    Route::get('/reception/show/{reservid}', [ReserveReceptionController::class, 'show'])->name('ReserveReception.show');
    Route::get('/reception/topdf/{reservid}', [ReserveReceptionController::class, 'topdf'])->name('ReserveReception.topdf');

    //  付属商品の登録
    Route::get('/user_accessories/create', [UserAccessoryController::class, 'create'])->name('user_accesories.create');
    Route::post('/user_accessories/post', [UserAccessoryController::class, 'store'])->name('user_accesories.store');


    Route::post('/user_products', [UserProductController::class, 'store'])->name('user_products.store');

    //  商品の編集
    Route::get('/user_products/{product}/edit', [UserProductController::class, 'edit'])->name('user_products.edit');
    //  付属商品の編集
    Route::get('/user_products/{product}/edit', [UserProductController::class, 'edit'])->name('user_products.edit');

    // ★ 商品の編集結果保存 (PUT/PATCH /user_products/{product})
    Route::put('/user_products/{product}', [UserProductController::class, 'update'])->name('user_products.update');

    //  コースの編集
    Route::get('/user_courses/{product_id}', [UserCourseController::class, 'create'])->name('user_courses.create');
    Route::post('/user_courses', [UserCourseController::class, 'store'])->name('user_courses.store');

    //  コース料金設定の編集
    Route::get('/user_courses_price/{course_id}', [UserCoursePriceController::class, 'create'])->name('UserCoursePrice.create');
    Route::post('/user_courses_price', [UserCoursePriceController::class, 'store'])->name('UserCoursePrice.store');
});


//------------------------------------------------------------
//◆◆◆◆◆◆◆◆◆◆◆  予約登録関係  ◆◆◆◆◆◆◆◆◆◆◆◆◆◆
//------------------------------------------------------------
//  予約入力画面
Route::get('reserve/create/{ShopID?}/{ProductID?}/{ReqDate?}', [ReserveController::class,'create'])->name('reserve.create');
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
//  予約の表示 店舗用
Route::get('reserve/show/{id}', [ReserveController::class,'show'])->name('reserve.show');
//  予約の表示 ゲスト用
Route::get('/reserve/showreserve', [ReserveController::class, 'showreserve'])->name('reserve.showreserve');

//  予約の編集
Route::get('reserve/edit/{reserve}', [ReserveController::class,'edit'])->name('reserve.edit');
//  予約の編集結果を書き込み
Route::patch('reserve/{reserve}', [ReserveController::class,'update'])->name('reserve.update');
//  予約の削除
Route::delete('reserve/{reserve}', [ReserveController::class,'destroy'])->name('reserve.destroy');

//  指定日情報の読み込み (BackEND)
Route::get('reserve/readDateInfo', [ReserveDateController::class,'readDateInfo'])->name('reserve.readDateInfo');
//  指定日情報の書き込み (BackEND)
Route::post('reserve/writeDateInfo/{status?}', [ReserveDateController::class,'writeDateInfo'])->name('reserve.writeDateInfo');



// 予約カレンダー表示 
Route::get('reserve/calender/{user_id}/{product_id}', [ReserveController::class, 'calender'])->name('reserve.calender');
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

// データをJSONで取得するエンドポイント (Ajax/API)
Route::get('/reserve/reserveIndex', [ReserveController::class, 'reserveIndex'])->name('reserve.index');

require __DIR__.'/auth.php';
