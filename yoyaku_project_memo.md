
<style>
ul {
  /*2px 水色 破線を指定*/
  border: 2px skyblue dashed; 
  line-height: 0.92em;
}
li {
	list-style-type: square;
    font-size:1.0rm;
    color: #434caf;
}
.f-container {
  display:flex; 
  height:200px;
}
.blueBox {
    margin: 10px;
    padding: 2px;
    background-color: #A0A0ff;
    color: #000000;
}
</style>

# URL

トップ画面
http://127.0.0.1/yoyaku/public/home


店舗情報の登録
http://127.0.0.1/yoyaku/public/register



            
<img src="{{ url('../resources\images\backimage1.jpeg') }}" alt="Back Image 1">

<svg viewBox="0 0 500 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  @auth
    <text id="myText" x="50%" y="50%" text-anchor="middle" font-size="20px">{{ Auth::user()->spName }}</text>
  @endauth
  @guest
    <text id="myText" x="50%" y="50%" text-anchor="middle" font-size="20px">あちゃまＷＥＢ開発</text>
  @endguest
  <animate xlink:href="#myText" attributeName="font-size" from="10px" to="30px" dur="3s" fill="both" />
</svg>

app\Http\Controllers\ReserveController.php
└shopsel()で $SelShopをセット   View::share('SelShop', $user);


        @isset($SelShop)
            <text class="textbox" id="shopname" text-anchor="middle" font-size="20px">{{ $SelShop->spName }}</text>
        @else
            <text class="textbox" id="shopname" text-anchor="middle" font-size="20px">予約案内</text>
        @endisset


Login 画面
app\Http\Controllers\Auth\AuthenticatedSessionController.php
└create()でLogin画面を表示
  resources\views\auth\login.blade.php
└store()でログイン処理




以下のcssを生成して
背景の<div class="head_frame"要素は横１００％でurl('{{ asset('images/slide_back.jpg') }}')を敷き詰める
その中に<div class="head_inbox"> で<img class="head_image"> と<div class="textbox">を囲む
<img class="head_image"> でhead_mounten.pngを中央に配置。
<img class="head_image">は高さを最小200pxから最大600pxで画面の幅に合わせて伸縮させ、幅は最小780pxとする
<div class="textbox">は<div class="head_inbox">内に置き、<img class="head_image">の左上から4pxに配置する

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();


        $request->session()->regenerate();

        View::share('SelShop', Auth::user());   // ログインユーザーを共有変数にセット

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function store(LoginRequest $request): View
    {
        $request->authenticate();

        $request->session()->regenerate();

        $controller = new ReserveController();
        return $controller->shopsel($request,Auth::user()->id);       
    }



yamucya
yamucyayamucya


Laravelのルート
http://127.0.0.1/yoyaku/public/
にアクセスし、web.phpに

Route::get('/', function () {
    return view('welcome');
});
と書いても
The GET method is not supported for route /. Supported methods: HEAD, POST, PUT, PATCH, DELETE, OPTIONS.
とエラーになる原因は？


-----------------------------------------------------------

resources\views\layouts\app.blade.phpがベース
「resources\views\layouts\navigation.blade.php」
app.blade.php
└「resources\views\components\application-logo.blade.php」--- start
logoのフォルダ: C:\Tools\AnHttpd\nmaki\yoyaku\public

url path: http://127.0.0.1/yoyaku/public/images/backimage1.jpeg

helper path: C:\Tools\AnHttpd\nmaki\yoyaku\resources\images

トップ画像
やむちゃ 管理画面
「resources\views\components\application-logo.blade.php」--- end


- 予約 -
カレンダーより、ご希望の予約日をお選びください。
　：
　：
app.blade.php -- END

-----------------------------------------------------------


--resource オプションを付けて実行することで、リソースコントローラーと呼ばれる一般的な CRUD (Create, Read, Update, Delete) 操作に必要なメソッドを自動生成できます。
php artisan make:controller UserProductController --resource

index(): リソースの一覧を表示
create(): 新しいリソースを作成するためのフォームを表示
store(): 新しいリソースを保存
show(): 特定のリソースを表示
edit(): 特定のリソースを編集するためのフォームを表示
update(): 特定のリソースを更新
destroy(): 特定のリソースを削除

リソースコントローラーを生成したら、routes/web.php ファイルにリソースルーティングを定義します。

PHP
Route::resource('user_products', UserProductController::class);



-----------------------------------------------------------

Laravelで以下のテーブルを作成した

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

