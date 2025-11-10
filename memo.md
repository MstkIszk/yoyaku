<link href="memo.css" rel="stylesheet"></link>
※ MarkDownのプレビューは VSCodeで [Ctrl]-[Shift]-[V] を押す
<style>
table th {
  background-color: #b0b0e0; /* ヘッダの背景色 */
  color: #40b371;/*文字色*/
}
table tr:nth-child(odd) {
  background-color: #e0e0e0; /* 奇数行の背景色 */
}
table tr:nth-child(even) {
  background-color: #cec0c0; /* 偶数行の背景色 */
}
h2 {
  background-color: #e0e0e0; /* 奇数行の背景色 */
  border-top: 4px solid;
  color: magenta;
}
h2::before {
	content: "★";
}
ul {
  /*2px 水色 破線を指定*/
  border: 2px skyblue dashed; 
}
li {
	list-style-type: square;
    font-size:1.2rm;
    color: #434caf;
}

</style>

環境変数の確認

> \>rundll32.exe sysdm.cpl,EditEnvironmentVariables

ルート設定の一覧表示

> \>php artisan route:list

## VsCode で XAMAPP をデバッグする設定

<ul>
<Li type="1"> ブラウザでphpinfo()を表示して全てコピー(CTRL-A)</li>
<Li type="1"> https://xdebug.org/wizard を開き、textAreaにペースト(CTRL-P)後 [phpinfo()の出力を分析する]をクリック</li>
<Li type="1"> php_xdebug-XXX.dllのリンクが現れるのでダウンロードして xamp\php\ext にコピー</li>
<Li type="1"> xamp\php\php.iniを開き、以下を追加  </li>
</ul>

```
[XDebug]
xdebug.remote_enable=1
xdebug.remote_autostart=1
;以下はサイトに記載されてたもの
zend_extension = D:\xampp\php\ext\php_xdebug-XXX.dll
```

◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆

## Laravel プロジェクト新規作成

#### Lolipop ログイン

> ssh chu.jp-kyum@ssh.lolipop.jp -p2222
> SMqeduZhkmeJe5sgEH2KkBdNF5rMRHjl

#### PHP のパスを通す

> export PATH=$PATH:/usr/local/php/8.2/bin/

#### Perl のパスを通す

> export PATH=$PATH:/usr/local/bin/perl/

.bash_profile を作成しておくと、毎回パス設定が不要になる

> vi ~/.bash_profile
> vi コマンドが見つからないとき
> /usr/bin/vi ~/.bash_profile
> perl の場所
> /usr/local/bin/perl -v

.bash_profile へ下記を記入
PATH="$PATH:/usr/local/php/8.2/bin"
:wq

###### bash_profile 設定に反映

source ~/.bash_profile

###### 現在の PATH 設定を確認するには

echo $PATH

#### composer をインストール

> php -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"

現在のフォルダに composer.phar が作られる

#### yoyaku プロジェクトを作成

> php composer.phar create-project laravel/laravel yoyaku --prefer-dist

パーミッションの変更

> $ chmod -R 777 storage
> $ chmod -R 775 bootstrap/cache

#### laravelcollective パッケージのインストール

> composer require laravelcollective/html

#### laravelcollective の削除

> composer remove laravelcollective/html

#### spatie/laravel-html パッケージののインストール

#### ＤＢの接続設定

<li>.env  の以下を変更</li>

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1　　　　　　　　　　　← ローカルデバッグ用
#DB_HOST=mysql303.phy.lolipop.lan　　← Loripop 用. アップロード時に変更
DB_DATABASE=LAA1590428-kyum
DB_USERNAME=LAA1590428
DB_PASSWORD=k1lt7Ovl
```

#### 言語設定

<li>config\app.php の以下を変更</li>

```
'timezone' => 'Asia/Tokyo',
'local' => 'ja',
'faker_local' => 'ja_JP',
```

<li>日本語化翻訳ファイルを作成</li>

> php artisan lang:publish

実行すると、プロジェクトフォルダ直下に\lang\フォルダが作成させる

Laravel Breeze 日本語化パッケージ（Breeze とは 認証画面の機能)
https://github.com/askdkc/breezejp

php.ini の以下のコメントを外す
extension=zip

組み込みコマンド

> \>composer require askdkc/breezejp --dev
> \>php artisan breezejp

これにより、\lang\フォルダに\ja\が作成され、日本語メッセージが定義されたファイルが作成される

日本語データは以下に定義する
lang\ja.json

```JSON
{
    "Name": "名前",
    "Profile": "アカウント",
    "Profile Information": "アカウント情報",
    "Cancel": "キャンセル",
　　　　：
```

使う場合は以下のように記述する

```html
{{ __('Profile') }}
```

lang\ja\validation.php 内の'attributes' で定義された文字列を view から参照するには

```html
{{ __('validation.attributes.name') }}
```

## 郵便番号から自動住所入力

① <form >　タグに class="h-adr" を追加

```html
<form class="h-adr" method="POST" action="{{ route('register') }}"></form>
```

② JS を取り込み

```html
<script
  src="https://yubinbango.github.io/yubinbango/yubinbango.js"
  charset="UTF-8"
></script>
```

③ 日本を設定
<span class="p-country-name" style="display:none;">Japan</span>

④ 入力フォーム

```html
<form class="h-adr">
  ・・・①
  <script
    src="https://yubinbango.github.io/yubinbango/yubinbango.js"
    charset="UTF-8"
  ></script>
  ・・・②
  <span class="p-country-name" style="display:none;">Japan</span>
  〒<input type="text" class="p-postal-code" size="3" maxlength="3" /> -<input
    type="text"
    class="p-postal-code"
    size="4"
    maxlength="4"
  /><br />
  <label>
    <span>都道府県</span><input type="text" class="p-region" readonly /><br />
  </label>
  <label>
    <span>市町村区</span><input type="text" class="p-locality" readonly /><br />
  </label>
  <label>
    <span>町域</span><input type="text" class="p-street-address" /><br />
  </label>
  <label>
    <span>以降の住所</span><input type="text" class="p-extended-address" />
  </label>
</form>
```

◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆◆

## コマンドライン(CLI)から MySQL に接続する

```
>C:\XAMPP\MySQL\BIN\mysql -u LAA1590428 -p -h localhost ` LAA1590428-kyum
password : k1lt7Ovl
```

## キャッシュのクリア

#### config ディレクトリ以下の設定ファイルのキャッシュを作成

.env や config/\*.php のファイルを編集したら実行しないと変更した設定情報が反映されない

> php artisan config:cache

#### 設定ファイルのキャッシュをクリア

> php artisan config:clear

#### アプリケーションのキャッシュをクリア

> php artisan cache:clear

#### ビューのキャッシュをクリア

> php artisan view:clear

#### ルートキャッシュを作り直します。

> php artisan route:cache

#### ルートのキャッシュをクリア

> php artisan route:clear

#### 8.0 以降ならまとめて消せる

> php artisan optimize:clear

#### autoload を自動で生成

> composer dump-autoload
> Composer は、PHP の依存性管理ツールで、composer.json ファイルに記述された依存ライブラリやクラスの情報をもとに、vendor/autoload.php というオートロードファイルを生成。
> composer.json ファイルには、プロジェクトで使用するすべてのクラスファイル（ライブラリ、フレームワーク、アプリケーションのクラスなど）のパスが登録されています。

新たなクラス定義やファイル定義を行うたびに実行します。
git pull で大きな変更があった場合は実行するといいです。

## ブラウザから Larabel ページへのアクセス

#### ブラウザで以下の url にアクセス

http://localhost/yoyaku/public/

ルート定義　 routes\web.php 　が呼ばれ

```php
Route::get('/', function () {
    return view('welcome');
});
```

ルートが呼ばれると
resources\views\welcome.blade.php
画面が開く

####　ブラウザからリクエストが来ると、以下の流れで Web ページが表示される

<ul>
<li >リクエスト</li>ブラウザからindex.phpへのリクエストが送られる
<li>初期化</li>index.phpがLaravelフレームワークを初期化し、ルーティング情報などを設定します。</li>
<li>ルーティング</li>リクエストに基づいて、適切なコントローラーのメソッドが実行されます。</li>
<li>ビューの選択</li>コントローラーのメソッドから、レンダリングするビュー（例えば、return view('welcome');）が指定されます。</li>
<li>ビューのレンダリング</li>指定されたビュー（welcome.blade.phpなど）が、app.blade.phpを継承し、最終的なHTMLコードが生成されます。</li>
<li>レスポンス</li>生成されたHTMLコードがブラウザに送信され、Webページとして表示されます。</li>
</ul>

## ログファイルの場所

"C:\Tools\AnHttpd\nmaki\yoyaku\storage\logs\laravel.log"

## 認証画面として Breeze を追加

breeze を使用するには composer を使用する
composer とは laravel において使用可能な便利なフレームワークやライブラリを管理するシステム
composer があることで、例えば A というライブラリをダウンロードする際に他に必要なライブラリも自動的にダウンロードしてくれる

#### 以下のコマンドを入力すると認証画面が追加される

> \>composer require laravel/breeze

実行すると yoyaku\composer.json ファイルに Breeze のバージョン情報が入る

```
    "require": {
        "php": "^8.1",
        "guzzlehttp/guzzle": "^7.2",
        "laravel/breeze": "^1.29",			←─── Breezeが入る
        "laravel/framework": "^10.10",
        "laravel/sanctum": "^3.3",
        "laravel/tinker": "^2.8"
    },
```

#### プロジェクトへの組み込み

> php artisan breeze:install blade

app\Http\Controllers\Auth フォルダ内に幾つかのソースが追加され、ブラウザで開くと画面左上にメニュー「login (ログイン)」と「Resister (登録)」が追加されている

#### テーブルの定義を追加

> \> php artisan make:migration create_XXXX_table

実行すると以下の PHP ファイルが作成＆追加される
yoyaku\database\migrations\2019_12_14_000001_create_XXXX_table.php

<table><tr><td>
参考：データベースのログイン処理は以下に定義されている
yoyaku\vendor\laravel\framework\src\Illuminate\Database\Connectors\Connector.php

```
<?php
　　　　　：
class Connector
{
    use DetectsLostConnections;
　　　　　：
```

<td></tr></table>

## artisan make: の種類

make は Laravel フレームワークで提供されるコマンドラインツールで、様々な種類の PHP ファイルを生成することができます
開発者は定型的なコードを書く手間を省き、より本質的な開発に集中することができます。

<table border=2>
<tr><th>コマンド</th><th>	ファイルの種類</th><th>	説明</th><tr>
<tr><td>php artisan make:controller	</td><td>コントローラー	</td><td>HTTP リクエストを受け取り、ビジネスロジックを実行し、ビューをレンダリングするクラス</td></tr>
<tr><td>php artisan make:model      </td><td>モデル	</td><td>データベースのテーブルに対応するクラス</td></tr>
<tr><td>php artisan make:migration	</td><td>マイグレーション	</td><td>データベースのスキーマを変更するためのファイル</td></tr>
<tr><td>php artisan make:request	</td><td>リクエスト	</td><td>HTTP リクエストのバリデーションを行うクラス</td></tr>
<tr><td>php artisan make:event      </td><td>イベント	</td>
    <td>アプリケーション内で発生するイベントを表すクラス<br>イベントリスナーと組み合わせて利用する</td></tr>
<tr><td>php artisan make:job	    </td><td>ジョブ	</td>
    <td>非同期処理を実行するためのクラス<br>キューに登録して、後で実行することができます。</td><tr>
<tr><td>php artisan make:test	    </td><td>テスト	</td><td>ユニットテストや機能テストを作成するためのクラス</td></tr>
<tr><td>php artisan make:policy	    </td><td>ポリシー	</td>
    <td>認証処理を行うクラス<br>ユーザーが特定のリソースにアクセスできるかどうかを判断します。</td></tr>
<tr><td>php artisan make:seeder	    </td><td>シード	</td><td>データベースに初期データを投入するためのクラス<br>
                                                            >php artisan db:seed [--class=Seederファイル] </td></tr>
<tr><td>php artisan make:command	</td><td>コマンド	</td><td>カスタムのコマンドを作成するためのクラス</td></tr>
</table>

## フォームの追加

#### Laravel フォームを追加するには 最低限以下 を作成する

<table border=2>
<tr><th rowspan=2>モデル           </th><td>\app\Models\ *.php </td><td>
                                    Eloquent（DBのデータを操作する実装）の機能とビジネスロジックを持つ</td></tr>
                                    <tr><td colspan=2>php artisan make:model </td</tr>
<tr><th>マイグレーション </th><td>\database\migrations\ * .php </th><td>
                                    データベース定義</td></tr>
<tr><th>ビュー           </th><td>\resources\views\post\ *.blade.php</th><td>
                                    GUIフォームを定義</td></tr>
<tr><th>コントローラー   </th><td>\app\Http\Controllers\ *Controller.php</th><td>
                                    web.phpから呼ばれる関数を定義し、対象となるviewファイルを返す</td></tr>
<tr><th>ルート設定を追加 </th><td>\routes\web.php</th><td>
                                    ブラウザからの入口でルートを定義。
                                    methodとパスにより使用するコントローラーを定義する</td></tr>
</table>

#### モデル(ファイル)の新規作成</li>

モデルファイルを作成時に、関連するファイルも生成する場合は、以下の [option]指定を行う

> \>php artisan make:model モデル名 [option]

<table border=1>
<tr><th>option            </th><th> モデル</th><th>マイグ<br>レーション</th><th>コントローラ</th><th>シーダ </th><th>ファクトリ</th><th>フォーム<br>リクエスト</th><th>ポリシー</th></tr>
<tr><td>--all , -a        </td><td>●      </td><td>●                  </td><td>●          </td><td>●      </td><td>●         </td><td>●                    </td><td>●</td></tr>
<tr><td>--controller , -c </td><td>●      </td><td>-                  </td><td>●          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--factory , -f    </td><td>●      </td><td>-                  </td><td>-          </td><td>-      </td><td>●         </td><td>-                    </td><td>-</td></tr>
<tr><td>--force<br>上書き </td><td>●      </td><td>-                  </td><td>-          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--migration , -m  </td><td>●      </td><td>●                  </td><td>-          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--policy          </td><td>●      </td><td>-                  </td><td>-          </td><td>-      </td><td>-         </td><td>-                    </td><td>●</td></tr>
<tr><td>--seed  , -s      </td><td>●      </td><td>-                  </td><td>-          </td><td>●      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--pivot , -p      </td><td>●      </td><td>-                  </td><td>-          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--resource ,-r    </td><td>●      </td><td>-                  </td><td>●          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--api             </td><td>-      </td><td>-                  </td><td>-          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
<tr><td>--requests, -R    </td><td>●      </td><td>-                  </td><td>●          </td><td>-      </td><td>-         </td><td>● Store<br>Update    </td><td>-</td></tr>
<tr><td>--test            </td><td>-      </td><td>-                  </td><td>-          </td><td>-      </td><td>-         </td><td>-                    </td><td>-</td></tr>
</table>

#### Eloquent の規定

###### テーブル名

クラスの複数形の「スネークケース」をテーブル名として使用。
この場合、Eloquent は Flight モデルが flights テーブルにレコードを格納し、AirTrafficController モデルは air_traffic_controllers テーブルにレコードを格納すると想定できます。
モデルの対応するデータベーステーブルがこの規約に適合しない場合は、モデルに table プロパティを定義してモデルのテーブル名を自分で指定できます。

```php
class Flight extends Model
{
    protected $table = 'my_flights';    //  モデルに関連付けるテーブル
}
```

###### 主キー

各モデルの対応するデータベーステーブルに id という名前の主キーカラムがあることも想定している。
主キーを変更する場合は、モデルの protected $primaryKey プロパティを定義する。

```php
class Flight extends Model
{
    // テーブルに関連付ける主キー
    protected $primaryKey = 'flight_id';
}
```

主キーは増分整数値であることも想定しています。これは、Eloquent が主キーを自動的に整数にキャストすることを意味します。非インクリメントまたは非数値の主キーを使用する場合は、モデルに public の$incrementing プロパティを定義し、false をセットする必要があります。

主キーが整数でない場合は、モデルに protected な$keyType プロパティを定義する必要があります。このプロパティの値は string にする必要があります。

```php
class Flight extends Model
{
    public $incrementing = false;   //  自動インクリメント無し
    protected $keyType = 'string';  //  主キーは文字列
}
```

##### 主キータイムスタンプ

モデルと対応するデータベーステーブルに、created_at カラムと updated_at カラムが存在していると想定します。Eloquent はモデルが作成または更新されるときに、これらの列の値を自動的にセットします。これらのカラムが Eloquent によって自動的に管理されないようにする場合は、モデルに$timestamps プロパティを定義し、false 値をセットします。
タイムスタンプの保存に使用するカラム名をカスタマイズする必要がある場合は、モデルに CREATED_AT および UPDATED_AT 定数を定義

```php
class Flight extends Model
{
    public $timestamps = false;     // モデルにタイムスタンプを付けるか
    protected $dateFormat = 'U';     // モデルの日付カラムの保存用フォーマット

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
}
```

###### デフォルト属性値

```php
class Flight extends Model
{
    protected $attributes = [
        'delayed' => false,
    ];
}
```

###### データベースにデータを登録または更新したい場合は、fillable または guarded を記載する。

<ul>
<li> $fillableはホワイトリスト方式としてデータ登録、更新を許可するカラムを指定します。</li>

```php
protected $fillable = [
        'OrderNo',      //  予約番号');
        'KeyStr',       //  照会時に比較する');
            :
];
```

</ul>

<ul>
<li>$guardedはブラックリスト方式としてデータ登録、更新を許可しないカラムを指定。指定したカラム以外は許可される。</li>

```php
protected $guarded = [
        'id',
            :
];
```

</ul>

<ul>
<li>$castsでカラムに対しての型を定義する</li>
モデルで型を定義しておけば、データ取得時に指定した型で取得できる。

```php
protected $casts = [
    'name' => 'string',
    'price' => 'integer',
    'flag' => 'boolean'
];
```

</ul>

cast 指定の種類

<table border=1>
<tr><th>型名</th>           <th> 機能</th></tr>
<tr><td>Integer</td>        <td>数字へ変換</td></tr>
<tr><td>float(real, double)</td><td>小数点つきの数字に変換</td></tr>
<tr><td>String</td>         <td>文字列化</td></tr>
<tr><td>boolean</td>        <td>trueもしくは、falseに変換<br>　true　・・・　1, 2, -1<br>　
false　・・・　0, 文字列の空白</td></tr>
<tr><td>object</td>         <td>PHPの「stdClass」に変換される<br>
            'colors' => ['red' => '赤', 'blue' => '青', 'yellow' => '黄色'    ]<br>
    のように定義すると json化されてデータベースに格納される
    </td></tr>
<tr><td>array</td>          <td>配列</td></tr>
<tr><td>collection</td>     <td></td></tr>
<tr><td>date, datetime</td> <td></td></tr>
<tr><td>timestamp</td>      <td></td></tr>
<tr><td>encrypted</td>      <td>暗号化</td></tr>
</table>

<li>リレーションを定義する</li>
<table border=1>
<tr><th>メソッド</th><th> 機能</th></tr>
<tr><td>hasOne</td><td>1対1：親テーブルから見た子テーブルのリレーション定義</td></tr>
<tr><td>belongsTo</td><td>1対1：子テーブルから見た親テーブルのリレーション定義</td></tr>
<tr><td>hasMany</td><td>1対多：親テーブルから見た子テーブルのリレーション定義</td></tr>
<tr><td>belongsToMany</td><td>1対多：子テーブルから親テーブルのリレーションを定義</td></tr>
</table>

#### テーブルの新規作成

マイグレーションファイルの新規作成

> \>php artisan make:migration マイグレーションファイル名 [option]

<table border=1>
<tr><th>option         </th><th> 機能</th></tr>
<tr><td>無し </td><td>空のmigration phpを生成
具体的には空の up() / down() 関数が作られる

```php
    public function up(): void {
    }
    public function down(): void {
    }
```

<tr><td>--create=tests </td><td>「tests」というテーブルを作成するphpを生成
具体的には up() / down() 関数に以下の定義が入る

```php
    public function up(): void {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tests');
    }
```

</td><td></td></tr>
<tr><td>--table=tests  </td><td>「tests」というテーブルの構造を変更するphpを生成
具体的には up() / down() 関数に以下の定義が入る

```php
        Schema::table('tests', function (Blueprint $table) {
            //
        });
```

</td><td></td></tr>
</table>

migrate: xxx コマンド

<table border=1>
<tr><td>php artisan migrate:status              </td><td> マイグレーションの状態確認</td></tr>
<tr><td>php artisan migrate [--path=<i>migratonファイル名</i>]</td><td> マイグレーション実行<br>
                                                            composer dump-autoload「クラスが見つかりません」エラーが発生したら、migrateコマンドを再発行してみる</td></tr>
<tr><td>php artisan migrate:refresh             </td><td> 初期化→再実行（マイグレーションファイル追加でエラーが出た時など）<br>
--path=/database/migrations/XXXX_table</td></tr>
<tr><td>php artisan migrate:fresh               </td><td> 全テーブル削除 →マイグレーション実行</td></tr>
<tr><td>php artisan migrate:rollback            </td><td> 直前のマイグレーションをロールバック</td></tr>
<tr><td>php artisan migrate:rollback [--step=n]   </td><td> ロールバック（戻る位置指定）</td></tr>
<tr><td>php artisan migrate:reset               </td><td> 全マイグレーションをリセット（初期化）<br>
ファイル指定の場合は以下オプションを追加<br>
<b> --path=database\migrations\"ファイル名"_table.php</b></td></tr>
</table>

## テーブル構造の変更

> \>php artisan make:migration add_test_column --table=users

```php
   INFO  Migration [C:\Tools\AnHttpd\nmaki\yoyaku\database\migrations/2024_08_03_100631_add_test_column.php] created successfully.
```

上記コマンドにより、yyyy_mm_dd_hhmmss_add_test_column.php ファイルが作成される

database\migrations\2024_08_03_100631_add_test_column.php

```java
return new class extends Migration
{
    /** migrations実行 */
    public function up(): void
    {   //  'test'カラムを追加する処理
        Schema::table('users', function (Blueprint $table) {
            $table->increments('id');                //  自動加算のID
            $table->string('email')->after('id');    //  追加するカラム情報 Text型
            $table->dateTime('enterDate')            //  日付け型
            $table->integer('kaisu')->comment('回数');

            $table->rememberToken();        //  トークン(パスワードリセット用)
            $table->timestamps();           //  タイムスタンプ(updated_atとcreated_at)
        });
    }

    /** migrations 解除 */
    public function down(): void
    {   //  'test'カラムを削除する処理
        Schema::table('users', function (Blueprint $table) {
            //削除するカラム情報
            $table->dropColumn('test');
        });
    }
};
```

<table>
<thead>
<tr><th>メソッド</th><th>データ型</th><th>説明</th></tr>
</thead>
<tbody>
<tr><td>string</td><td>VARCHAR</td><td>可変長の文字列　255文字まで</td></tr>
<tr><td>char('カラム名', 長さ)</td><td></td><td>	長さ指定の文字型</td></tr>
<tr><td>double('カラム名', 合計桁数, 小数桁数)</td><td></td><td>浮動小数点型</td></tr>
<tr><td>enum('カラム名', ['定数', '定数'])</td><td></td><td>$table->enum('status', ['Draft', 'Published', 'Archived']);</td></tr>
<tr><td>json('カラム名')</td><td></td><td>JSON型	</td></tr>
<tr><td>binary('カラム名')</td><td></td><td>バイナリデータ型</td></tr>
<tr><td>text</td><td>TEXT</td><td>大きなテキストデータを格納。最大16384文字</td></tr>
<tr><td>longText</td><td>TEXT</td><td>大きなテキストデータを格納する　１GB</td></tr>
<tr><td>increments</td><td>符号なしINT</td><td>1から始まり、次のレコードは2、3と自動的に増加する整数。自動的に主キーとして設定されます</td></tr>
<tr><td>integer</td><td>int</td><td>整数</td></tr>
<tr><td>boolean</td><td>真偽値</td><td>真偽値（trueまたはfalse）</td></tr>
<tr><td>char</td><td>文字列</td><td>指定した長さの文字列を格納するカラム</td></tr>
<tr><td>date</td><td>date</td><td>日付</td></tr>
<tr><td>dateTime</td><td>dateTime</td><td>日付と時間が「YYYY-MM-DD HH:MM:SS」の形式で年、月、日、時、分、秒の情報が含まれています。</td></tr>
<tr><td>timestamp</td><td>timestamp</td><td>現在の日時</td></tr>
<tr><td>timestamps</td><td>timestamps</td><td>作成日(created_at)と更新日(updated_at)を作成する</td></tr>
</tbody>
</table>

データベースカラムに制約を追加するためのメソッド</h1>

<table>
<thead>
<tr><th>メソッド</th><th>使用例</th><th>説明</th></tr>
</thead>
<tbody>
<tr><td>first</td><td><code>string('name')-&gt;first()</code></td><td>指定したカラムのテーブルの最初のカラムに設定する</td></tr>
<tr><td>after</td><td><code>string('name')-&gt;after('email')</code></td><td>指定したカラムの次にカラムを追加する</td></tr>
<tr><td>unique</td><td><code>string('email')-&gt;unique()</code></td><td>同じ値がデータベーステーブルの指定したカラムに重複して保存されることを防ぐ</td></tr>
<tr><td>change</td><td><code>string('email')-&gt;nullable()-&gt;change()</code></td><td>カラムの属性を変更する</td></tr>
<tr><td>comment</td><td><code>text('content')-&gt;comment('blog')</code></td><td>カラムにコメント追加する</td></tr>
<tr><td>default</td><td><code>string('status')-&gt;default('active')</code></td><td>カラムのデフォルト値を設定する</td></tr>
<tr><td>nullable</td><td><code>string('email')-&gt;nullable()</code></td><td>NULL値をデフォルトでカラムに挿入する</td></tr>
<tr><td>nullable</td><td><code>string('email')-&gt;nullable(false)</code></td><td>NULL値を許容しない</td></tr>
</tbody>
</table>

## migrate コマンドでカラムを追加する

> /> php artisan migrate

```php
   INFO  Running migrations.

  2024_08_03_100631_add_test_column .................................................................... 28ms DONE
```

add_test_column.php の up()関数が呼ばれて、'test'カラムが追加される

migrate で全ての migrate を削除するには以下のコマンドを入力

> \> php artisan migrate:reset

```php
   INFO  Rolling back migrations.

  2024_08_03_100631_add_test_column ............................. 2ms DONE
  2019_12_14_000001_create_personal_access_tokens_table ......... 20ms DONE
  2019_08_19_000000_create_failed_jobs_table .................... 14ms DONE
  2014_10_12_100000_create_password_reset_tokens_table .......... 15ms DONE
  2014_10_12_000000_create_users_table .......................... 14ms DONE
```

特定のテーブルのみ migrate

> \>php artisan migrate:refresh --path=/database/migrations/2021_08_28_061818_create_boards_table.php

</ul>

php artisan session:table

## コントローラーの作成

<ul>
<li type="1"> コマンド</li>

> \>php artisan make:controller TestController

ソース yoyaku\app\Http\Controllers\TestController.php が作成される

<li type="1"> 関数実装</li>
TestController.phpの class本体に test() 関数を追加する
　：

```php
class TestController extends Controller
{
    // routes\web.php から呼ばれる
    public function test() {
        return view("test"); ←yoyaku\resources\views\test.blade.phpを表示せよ
    }
}
```

<li type="1"> 表示内容を定義</li>

yoyaku\resources\views\test.blade.php に表示内容を定義する

```
    こんにちわ
```

<li type="1"> 使用宣言</li>
生成されたControllerを使用するため routes\web.php に use 宣言を追加

```php
use App\Http\Controllers\TestController;
```

<li type="1"> 使用</li>
routes\web.php に呼び出しを追加

Route::get('/ルート定義', 宣言方法 [ファイル/クラス名::class, '関数名'])

```php
Route::get('/test', [TestController::class, 'test'])
    ->name('test');
```

</ul>

■ まとめ ■
http://127.0.0.1/yoyaku/public/test で任意のページを表示させるには
Controller を作成 → view に表示内容を定義 → routes\web.php で引数を定義

## View ファイルで使用されている共通定義

共通定義は　\resources\views\components\ に定義されている

<table>
<tr><th >　　　定義　　　</th><th>　　　　　　　ファイル名　　　　　　　</th></tr>

<tr><td>x-application-logo  </td><td>application-logo.blade.php</td></tr>
<tr><td>auth-session-status.blade.php</td><td></td></tr>
<tr><td>cal-date.blade.php</td><td></td></tr>
<tr><td>danger-button.blade.php</td><td></td></tr>
<tr><td>input-error.blade.php</td><td></td></tr>
<tr><td>input-label.blade.php</td><td></td></tr>
<tr><td>message.blade.php</td><td></td></tr>
<tr><td>modal.blade.php</td><td></td></tr>
<tr><td>primary-button.blade.php</td><td></td></tr>
<tr><td>r-textbox.blade.php</td><td></td></tr>
<tr><td>secondary-button.blade.php</td><td></td></tr>
<tr><td>text-input.blade.php</td><td></td></tr>

<tr><td>x-nav-link          </td><td>nav-link.blade.php</td></tr>
<tr><td>x-dropdown          </td><td>dropdown.blade.php</td></tr>
<tr><td>x-slot              </td><td></td></tr>
<tr><td>x-dropdown-link</td><td>dropdown-link.blade.php</td></tr>
<tr><td>x-responsive-nav-link</td><td>responsive-nav-link.blade.php</td></tr>
<table>

コンポーネントクラスがない場合:

コンポーネントクラス (app/View/Components/ApplicationLogo.php) が存在しない場合、Laravel は単純に application-logo.blade.php をビューファイルとしてレンダリングします。
つまり、コンポーネントクラスがなくても、Blade ファイルが存在すればコンポーネントとして機能します。
ApplicationLogo.php

## View 操作

<table>
<tr><th >　　　ディレクティブ　　　</th>
<th>　　　　　　　役割 　　　　　　　</th></tr>

<tr><td>@php ～ @endphp</td><td>php 処理</td></tr>
<tr><td>@if(条件)～ @endif</td><td>条件分岐</td></tr>
<tr><td>@switch</label>(評価値)<br>
                        　@case(条件値)<br>
                        　@default(条件値)<br>
                        @endswitch</td>
                        <td>条件分岐</td></tr>
<tr><td >@unless (変数)</td><td >条件非成立の時表示</td></tr>
<tr><td >@empty  (変数)～@endempty</td><td >変数が空の場合表示</td></tr>
<tr><td >@isset  (変数)～@endisset</td><td >変数が定義済みの場合表示</td></tr>
<tr><td >@for    (初期化 ; 条件; 後処理;)～@endfor</td><td >PHPのfor構文に相当するもの</td></tr>
<tr><td >@foreach    ($配列 as $変数)</td><td >PHPのforeach構文に相当するもの</td></tr>
<tr><td >@forelse ($配列 as $変数)<br>@empty</td><td >PHPのforeachに加え、@emptyで空だったときの処理が記述できる</td></tr>
<tr><td >@while  (条件)～@endwhile</td><td >PHPのwhile構文に相当するもの</td></tr>
<tr><td >@section   (名前)</strong></td><td >指定した名前でセクションが用意される</td></tr>
<tr><td >@parent    </td><td >親レイアウトのセクションを示す</td></tr>
<tr><td >@yield (名前)</strong></td><td >
    <b><font size=+1>レイアウトとコンテンツを分離するために使用</font></b>
    <li><b>セクションの定義</b></li> 親テンプレート（レイアウト）で、子テンプレート（ビュー）から内容を埋め込むための「セクション」を定義します。
    <li><b>セクションへの内容の埋め込み</b></li> 子テンプレートでは、@sectionディレクティブを使って、親テンプレートで定義されたセクションに内容を埋め込みます。</td></tr>
<tr><td >@extends    (Bladeのファイル名)</td><td ><li><b>レイアウトの継承設定</b></li>
    ヘッダー、フッターなど複数のビューで共通する部分を一つのテンプレートにまとめ、他のビューからそのテンプレートを継承することで、コードの重複を減らし、メンテナンス性を向上させる</td></tr>
<tr><td >@component  (名前)</td><td >コンポーネントの組み込み</td></tr>
<tr><td >@slot   (名前)</td><td >{{}}で指定された変数に値を設定する</td></tr>
<tr><td >@include    (読み込むテンプレート名,[値の指定])</td><td >サブビューの読み込み</td></tr>
<tr><td >@each   (テンプレート名, 配列, 配列から取り出したデータを入れる変数名)</td><td >配列などから値を取り出し指定のテンプレートにはめ込んで出力する</td></tr>
</table>

#### ディレクティブ使用例

```php
//  画面
@isset ($value)
    // $value が定義済みの場合表示する
    @foreach ($users as $user)
        <li>{{ $user->name }}</li>
        @if ($user->id == 5)
        // ループ終了
            @break
        @elseif ($value02)
        // 違う条件の場合で true だった場合の表示
        @else
        @endif

        // @loopが暗黙で定義され、回数が取得できる
        @if ($loop->first)              // 最初の繰り返し
        @if ($loop->index === 0)        // 最初の繰り返し(インデックスが取れる)
        @if ($loop->iteration === 1)    // 最初の繰り返し(現在の繰り返し数が取れる)
        @if ($loop->last)               // 最後の繰り返し
        @if ($loop->remaining === 1)    // 最後の繰り返し (残りの繰り返し数が取れる)

    @endforeach

@endisset

@yield('content')   //  ※サブコンポーネントの挿入位置

@auth
    @for ($i = 0; $i < 10; $i++)
    // 繰り返し処理
    {{ $i }} {{ Auth::user()->name }}さん、をつ!
    @endfor
@endauth
```

※ @yield 用のサブコンポーネント

```php
//  コンポーネント
@extends('layouts.app') //  子テンプレートで親テンプレートを継承することを宣言
                        //  継承元のテンプレート　\resources\views\layouts\app.blade.php

@section('content')     //  @yield('content') に埋め込まれる範囲
    <h1>Welcome</h1>
    <p>This is the home page.</p>
@endsection
```

## コンポーネントの利用

<ul>
<li> ボタン等を構成するファイルを以下に配置</li>
\resources\views\components\
\resources\views\dashboard.blade.php
</ul>

## テンプレート

<li> 通常テンプレート(app.blade.php)の配置</li>
\resources\views\layouts\app.blade.php
通常テンプレートは、ログイン後のユーザに表示するページのテンプレートとして使われる。

#### @Vite 定義

@Vite はテンプレートの\<head>～\</head>内に定義し、スタイルシートや JavaScript ファイルへのリンクを入れることで、このテンプレートを使用したページでリンクを使用できるようにする。

Vite は webpack、Rollup、Parcel のようなビルドツールの 1 つ。
これらの役割としては、プロジェクトすべてのファイルをコンパイル・結合して一つの塊（バンドル）となったファイルを生成してくれます。

```php
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
```

###### app.css には TailWind CSS の利用宣言が入る

```php
@tailwind base;
@tailwind components;
@tailwind utilities;
```

###### app.js には Alpine.js の利用宣言が入る

```php
import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

#### スロット定義

テンプレート内に

<li>{{ $header }} と書くと下記のheader定義内が展開される</li>
<li>{{ $slot }}と書くと下記のslot定義内が展開される</li>

```html
<x-app-layout>
    <x-slot name="header">
        header文字列
    </xslot>
    <x-slot name="slot">
        slotの文字列
    </xslot>
<x-app-layout>
```

</ul>

#### コントロールの定義方法

スタイルは「Tailwind CSS」で記述することが推奨されている
Tailwind CSS は、ユーティリティクラスを使って効率的にスタイリングできるフレームワーク

```html
<label for="{{ $name }}">{{ $slot }}</label>
<input
  type="{{ $type }}"
  name="{{ $name }}"
  id="{{ $name }}"
  class="py-2 border border-gray-300 rounded-md"
  value="{{ $value }}"
  placeholder="{{ $placeholder }}"
  {{
  $attributes
  }}
/>
```

<table border=2>
<tr><td rowspan=3> レイアウト</td>  <td> container</td>         <td> 
コンテナ要素を作成し、中央寄せと幅の調整を自動で行う

```html
<div class="container mx-auto">
  <!-- コンテンツ -->
</div>
```

</td></tr>

<tr>                                <td> flex </td>             <td> フレックスボックスのコンテナを作成</td></tr>
<tr>                                <td> grid </td>             <td> グリッドレイアウトのコンテナを作成</td></tr>
<tr><td rowspan=2> 幅・高さ</td>    <td>  w-full</td>           <td> </td></tr>
<tr>                                <td> h-screen </td>         <td> </td></tr>
<tr><td rowspan=2> 色</td>          <td> bg-blue-500</td>       <td> </td></tr>
<tr>                                <td> text-gray-800 </td>    <td> </td></tr>
<tr><td rowspan=2>余白</td>         <td>  p-4</td>              <td> </td></tr>
<tr>                                <td> m-4 など</td>          <td> </td></tr>
<tr><td rowspan=2>ボーダー</td>     <td>  border</td>           <td> </td></tr>
<tr>                                <td> border-gray-300 </td>  <td> </td></tr>
<tr><td rowspan=2>テキスト整形</td> <td> text-center</td>        <td> </td></tr>
<tr>                                <td> font-bold </td>        <td> </td></tr>
<tr><td rowspan=2>ホバー・レスポンシブ</td><td> hover:bg-blue-700</td> <td> </td></tr>
<tr>                                <td> md:text-lg </td>       <td> </td></tr>
</table>

## ルートの定義方法

ルーティングとは、クライアントからのアクセスに対応してどういう処理をするか決めること。
例えば、「http://.../○○ というアドレスにアクセスしたら、×× コントローラの △△ アクションを実行する」みたいな感じ

#### ルーティングを指定するには、function()で処理内容を記述する方法と、コントローラーを指定する方法がある

<ul>
<li>function指定の書き方</li>

```php
　　　　①↓　　　　　　②↓　　　　　　
Route::HTTPメソッド('URLパス', function($message) {
    return view('hello.index', ['msg'=>$message]);
});
```

<li>コントローラ指定の書き方</li>

```php
　　　　①↓　　　　　②↓　　　　　　③↓　　　　　　　　　　　④↓　　　　　　　　　⑤↓
Route::HTTPメソッド('URLパス', [コントローラー::class, 'メソッド名'])->name('ルート名');
```

</ul>

#### ① HTTP メソッド(動詞)に対応して、以下が使える

<table border=1>
<tr><th >　　　ディレクティブ　　　</th><th >　　　　　　</th></tr>
<tr><td><li> Route::get</li></td><td>データを取得する基本的なもの</td></tr>
<tr><td><li> Route::post</li></td><td>データの追加に使用</td></tr>
<tr><td><li> Route::put</li></td><td>データの更新に使用</td></tr>
<tr><td><li> Route::patch</li></td><td>ほぼPUTと同じですが、ごく一部を更新</td></tr>
<tr><td><li> Route::delete</li> </td><td>データの削除に使用</td></tr>
<tr><td><li> Route::options</li> </td><td>使えるメソッド一覧を表示</td></tr>
<tr><td><li> Route::redirect</li> </td><td>リダイレクト。302(一時的な転送)と 301(永続的な転送) がある<br>Route::redirect('/old-url', '/new-url');</td></tr>
</table>

#### function 指定による記述例

<table border=1>
<tr><td>

```php
// クロージャの中にpathに対応する処理を書く
Route::get('/', function() {
    return view('welcome');
});

//「'/index/test'とアクセスされたら、'test'が$messageに代入されて、helloフォルダのテンプレート'index'をブラウザに表示する」ということになる。
// この時、テンプレート'index'内の$msgに$message(つまり'test')が代入される。
Route::get('index/{msg}', function($message) {
    return view('hello.index', ['msg'=>$message]);
});

// 省略可能なパラメータの場合はパラメータ名($msg) の後ろに ? を書く
Route::get('index/{msg?}', function($message='Hello') ・・・


//idは$param1、nameは$param2に代入される
Route::get('index/{id}/{name}', function($param1, $param2) {
    return view('hello.index', ['id'=>$param1, 'name'=>$param2]);
});
```

</td></tr></table>

#### class 指定による記述例

<li>⑤ ルートに名前(->name()) を付けると、ビューやコントローラーで名前を指定して使用できるようになる</li>
<table border=1>
<tr><td>

```php
// 使用例
Route::get('/hello', [HelloController::class, 'index'])->name('hello');
```

上記の neme 定義を行うと、コントローラやビューで以下の <b>route{}</b> 定義が使用できる

```html
<a href="{{ route('hello') }}>リンク名</a>
// 引数がある場合
<a href="{{ route('hello',['id' => 1]) }}">テスト</a>
```

</td></tr></table>

<li>パラメータを指定する方法</li>
<table border=1>
<tr><td>

```php
// URLにパラメータ埋め込み
Route::get('path/{parameter}', [コントローラークラス名::class, 'メソッド名']);
// 使用例
Route::get('/users/{id}', [UserController::class, 'show']);
//  複数パラメータ指定
Route::get('/users/{id}/name/{name}', [UserController::class, 'show']);
```

XXXcontroller.php には以下を指定することになる

```php
public function index($id) { }
public function index($id,$name) { }
```

</td></tr></table>

#### Laravel のルーティングファイルには、web.php と api.php の 2 種類がある

###### web.php

通常、ブラウザから HTTP リクエストをうけて、画面に表示するようなルーティングを設定する場合に使用する。
CSRF 保護などの機能が有効になっているため外部からの POST ができない。

###### api.php

外部からの HTTP リクエストをうけて、値を返却したりするようなルーティング(エンドポイント)を設定する場合に使用する。
CSRF 保護が有効になっていないため外部から POST ができる。

## フォームの作成

フォームを追加するには <b>モデル</b>・<b>ビュー</b>と<b>コントローラーファイル</b>を作成する

#### モデルの作成

> \>php artisan make:model Post -m

モデルファイル
database\migrations\2024_08_05_174658_create_posts_table.php

```php
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');    ←追加
            $table->text('body');       ←追加
            $table->timestamps();
        });
    }
```

#### ビューファイルの作成

post フォルダを作成
resources\views\post\

resources\views\dashboard.blade.php ファイルをコピーして\post\create.blade.php ファイルを作成

> \resources\views\> copy dashboard.blade.php .\post\create.blade.php

\<form>内にコントロールを記述

> method="post" action="{{ route('フォーム送信先のルート設定') }}

```php
            <form method="post" action="{{ route('reserve.store') }}">
                @csrf
                <label for="reservation_date">予約日:</label>
                ：
            </form>
```

#### コントローラーファイルの作成

> \> php artisan make:controller PostController

```php
class PostController extends Controller
{
    //Post/create.blade.phpを表示
    public function create() {
        return view("post.create");
    }
}
```

```php
@isset ($value)
    // $value が定義済みの場合表示する
    @foreach ($users as $user)
        <li>{{ $user->name }}</li>
        @if ($user->id == 5)
        // ループ終了
            @break
        @elseif ($value02)
        // 違う条件の場合で true だった場合の表示
        @else
        @endif
    @endforeach

@endisset

@auth
    @for ($i = 0; $i < 10; $i++)
    // 繰り返し処理
    {{ $i }} {{ Auth::user()->name }}さん、をつ!
    @endfor
@endauth
```

## コンポーネントの利用

<ul>
<li> ボタン等を構成するファイルを以下に配置</li>
yoyaku\resources\views\components\
yoyaku\resources\views\dashboard.blade.php
</ul>

## テンプレート

<li> 通常テンプレートの配置</li>
yoyaku\resources\views\layouts\app.blade.php

Vite とは webpack、Rollup、Parcel のようなビルドツールの 1 つ。
これらの役割としては、プロジェクトすべてのファイルをコンパイル・結合して一つの塊（バンドル）となったファイルを生成してくれます。

```php
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
```

<li> app.cssには TailWind CSS の利用宣言が入る</li>

```php
@tailwind base;
@tailwind components;
@tailwind utilities;
```

app.js には Alpine.js の利用宣言が入る

```
import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

## スロット定義

#### 定義方法

name 属性でスロットの名前を指定
スロット内にコンテンツを記述

#### 定義例

<ul>
<table border=1>
<tr><td>*.brade.php</td><td>コンポーネント</td><td>展開後</td></tr>
<tr><td>

```xml
<x-app-layout>
    <x-slot name="header">  ・ 定義(A)
        header文字列
    </xslot>
    <x-app>                 ・ 定義(B)
        <h1>ホーム画面</h1>
        <p>ホーム画面のコンテンツです。</p>
    </x-app>
    <x-slot name="slot">  ・ 定義(C)
        slotの文字列
    </xslot>
    <x-slot name="footer">  ・ 定義(D)
        <p>これはフッターです</p>
    </x-slot>
<x-app-layout>
```

</td><td>

```html
<div>
  {{ $header }}

  <slot></slot>

  {{ $slot }} {{ $footer }}
</div>
```

</td><td>

```html
<div>
  header文字列

  <h1>ホーム画面</h1>
  <p>ホーム画面のコンテンツです。</p>

  slotの文字列

  <p>これはフッターです</p>
</div>
```

</td></table>
</ul>

## フォームの作成

#### モデルとマイグレーションの作成

> \>php artisan make:model Post -m

このコマンドにより、\app\Models と database\migrations\ に各々の php ファイルが作成される

###### モデルファイル作成

\app\Models\Post.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    //  保存・更新したいカラムを設定
    protected $filLable = [
        'title',
        'body'
    ];
}
```

###### マイグレーションファイル

database\migrations\2024_08_05_174658_create_posts_table.php

```php
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');    ←追加
            $table->text('body');       ←追加
            $table->timestamps();
        });
    }
```

#### ビューファイルの作成

post フォルダを作成

> \> md resources\views\post\

resources\views\dashboard.blade.php ファイルをコピーして\post\create.blade.php ファイルを作成

> \resources\views\> copy dashboard.blade.php .\post\create.blade.php

<details><summary> ＜form＞内にコントロールを記述</summary>

```html
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">フォーム</h2>
  </x-slot>
  <div class="max-w-7xl mx-auto px-6">
    <!-- セッションの中に messege が含まれていれば表示する -->
    @if(session('message'))
    <div class="text-red-600 font-bold">{{session('message')}}</div>
    @endif
    <form method="post" action="{{ route('post.store') }}">
      <!--  @csrf : cross site request forgeries 攻撃を防ぐための記述 
                  ワンタイムトークンを実装する -->
      @csrf
      <div class="mt-8">
        <div class="w-full flex flex-col">
          <label for="title" class="font-semibold mt-4">件名</label>
          <!-- エラーメッセージの実装 -->
          <x-input-error :messages="$errors->get('title')" class="mt-2" />
          <!-- エラーメッセージが表示されても、前回の入力値が残るようにするには old('項目名') と記述 -->
          <input
            type="text"
            name="title"
            class="w-atuo py-2 border border-gray-300 rounded-md"
            id="title"
            value="{{old('title')}}"
          />
        </div>
      </div>
      <div class="w-full flex flex-col">
        <label for="body" class="font-semibold mt-4">本文</label>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
        <textarea
          name="body"
          class="w-auto py-2 border border-gray-300 rounded-md"
          id="body"
          cols="30"
          rows="5"
        >
{{old('body')}}</textarea
        >
      </div>
      <x-primary-button class="mt-4"> 送信する </x-primary-button>
    </form>
  </div>
</x-app-layout>
```

</details>

#### Alpine.js とは

Alpine.js は、Vue.js に似た軽量な JavaScript フレームワークで、HTML 要素に JavaScript の振る舞いを追加することができる
x-data は、Alpine.js の主要なディレクティブの 1 つで、コンポーネントのデータを定義するために使用する

```html
x-data="{ isOpen: false }"

<nav x-data="{ isOpen: false }">
  ・・・・isOpenというデータを定義し、初期値をfalseとする
  <button @click="isOpen = !isOpen">
    ・・・・Clickされたら isOpenをtrueとする Menu
  </button>
  <ul x-show="isOpen">
    ・・・・isOpenがtrueならば以下を表示
    <li><a href="#">Link 1</a></li>
    <li><a href="#">Link 2</a></li>
    <li><a href="#">Link 3</a></li>
  </ul>
</nav>
```

###### 主なディレクティブ

<table>
  <thead>
    <tr><th>ディレクティブ</th><th>説明</th><th>使用例</th></tr>
  </thead>
  <tbody>
    <tr>
      <td><code>x-data</code></td>
      <td>コンポーネントのデータを定義する</td>
      <td><code>&lt;div x-data="{ isOpen: false }"&gt;...&lt;/div&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-init</code></td>
      <td>コンポーネントの初期化時に実行する JavaScript 式を指定する</td>
      <td><code>&lt;div x-data="{ message: '' }" x-init="message = 'Hello!' "&gt;...&lt;/div&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-on</code> (または <code>@</code>)</td>
      <td>DOM イベントをリッスンし、JavaScript 式を実行する</td>
      <td><code>&lt;button @click="isOpen = !isOpen"&gt;Toggle&lt;/button&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-model</code></td>
      <td>フォーム要素の値をデータにバインドする</td>
      <td><code>&lt;input type="text" x-model="name"&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-show</code></td>
      <td>式が truthy の場合に要素を表示する</td>
      <td><code>&lt;div x-show="isOpen"&gt;...&lt;/div&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-if</code></td>
      <td>式が truthy(TRUEと評価される値) の場合に要素をレンダリングする</td>
      <td><code>&lt;template x-if="isOpen"&gt;&lt;div&gt;Content&lt;/div&gt;&lt;/template&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-text</code></td>
      <td>要素のテキストコンテンツをデータにバインドする</td>
      <td><code>&lt;span x-text="message"&gt;&lt;/span&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-html</code></td>
      <td>要素の HTML コンテンツをデータにバインドする</td>
      <td><code>&lt;div x-html="htmlContent"&gt;&lt;/div&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-bind</code> (または <code>:</code>)</td>
      <td>要素の属性をデータにバインドする</td>
      <td><code>&lt;img :src="imageUrl"&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-for</code></td>
      <td>配列の各要素に対して要素を繰り返す</td>
      <td><code>&lt;template x-for="item in items"&gt;&lt;div&gt;...&lt;/div&gt;&lt;/template&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-cloak</code></td>
      <td>Alpine.js が読み込まれるまで要素を非表示にする</td>
      <td><code>&lt;div x-cloak&gt;...&lt;/div&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-ref</code></td>
      <td>要素への参照を作成する</td>
      <td><code>&lt;input type="text" x-ref="myInput"&gt;</code></td>
    </tr>
    <tr>
      <td><code>x-transition</code></td>
      <td>要素のトランジション（アニメーション）を制御する<br>
        以下の修飾子（modifier）を使って詳細な設定を行うことができる
        <li>x-transition:enter: 要素が表示される際のトランジション</li>
        <li>x-transition:leave: 要素が非表示になる際のトランジション</li>
        <li>x-transition:duration: トランジションの duration（時間）</li>
        <li>x-transition:ease: トランジションの easing（イージング関数） </li>       </td>
      <td><code>&lt;div x-show="isOpen" x-transition&gt;...&lt;/div&gt;</code></td>
    </tr>
  </tbody>
</table>

    <ul>



    </ul>

#### コントローラーファイルの作成

コントローラには入力チェック・Post ならば DB の更新等と View の呼び出し等を記述する。

###### コントローラ作成コマンド

> \> php artisan make:controller PostController

<details><summary> app\Http\Controllers\PostController.php ファイルが作成される</summary>

```php
//  postモデルを使うための宣言を追加
use App\Models\Post;

class PostController extends Controller
{
    //Post/create.blade.phpを表示
    public function create() {
        return view("post.create");
    }
    public function store(Request $request) {
        //  Request は フォームから送信されたデータを受け取っており、Illuminate\Http\Request を指している

        //  入力の妥当性チェック
        $validated = $request->validate([
            //      required : 必須入力
            //      max:nn : 最大文字列
            //      'integer | between:0,150' : 数値 0～150
            //      ['max:1', 'regex:/^[男|女]+$/u'],
            "title"=> "required|max:20",
            "body"=> "required|max:400",
            ],
            [
                //  エラーメッセージ記述
                'title.required' => 'タイトルは必須です。',
                'title.max' => 'タイトルは20文字以内。',
                'body.required' => 'bodyは必須項目です。'
            ]
        );

        //  Postモデルに沿ってPostインスタンスを作成
        //      create は resources\views\post\create.blade.php で処理される

        //  方法１　夫々の項目を引数で渡す
        if(false) {
            $post = Post::create([
                'title' => $request->title,
                'body'  => $request->body
            ]);
        }
        //  方法２　妥当性チェック済の値を渡す
        //      $validated は array(2) となっており title , body が含まれている
        $post = Post::create($validated);

        //  保存時のメッセージを表示
        $request->session()->flash('message','保存しました');
        //  処理後に元のページに戻る
        return back();
    }
}
```

</details>

###### よく使うバリデーションルール:Validate

<table border=1>
<tr><th>ルール </th><th >説明</th></tr>
<tr><td>required</td><td>必須</td></tr>
<tr><td>string</td><td>文字列であること</td></tr>
<tr><td>integer</td><td>整数であること</td></tr>
<tr><td>numeric</td><td>数値であること</td></tr>
<tr><td>email</td><td>メールアドレス形式であること</td></tr>
<tr><td>url</td><td>URL形式であること</td></tr>
<tr><td>ip</td><td>IPアドレス形式であること</td></tr>

<tr><td>date</td><td>日付形式であること</td></tr>
<tr><td>alpha	</td><td>アルファベットのみ</td></tr>
<tr><td>numeric	</td><td>数字のみ</td></tr>
<tr><td>alpha_num	</td><td>英数字のみ</td></tr>
<tr><td>min:n	</td><td>最小文字数</td></tr>
<tr><td>max:n	</td><td>最大文字数</td></tr>
<tr><td>size:n	</td><td>厳密にn文字</td></tr>
<tr><td>between:min,max	</td><td>min文字以上max文字以下</td></tr>
<tr><td>in:value1,value2,...	</td><td>指定された値のいずれか</td></tr>
<tr><td>カスタムバリデーション</td><td>

カスタムバリデーションファイルの場所: 任意の Controller や Service クラス内

```php
use Illuminate\Support\Facades\Validator;

Validator::extend('custom_rule', function ($attribute, $value, $parameters, $validator) {
    // カスタムバリデーションロジック
    return preg_match('/^[a-z]+$/i', $value);
});
```

###### 電話番号チェック

```php
$validatedData = Validator::make($request->all(), [
    'phone_number' => 'regex:/^0[0-9]{9,10}$/',
]);

Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
    // カスタムバリデーションロジック
    // 例: 日本の携帯電話番号のみ許可
    return preg_match('/^0[789][0-9]{8}$/', $value);
});

```

ハイフンありなし両方許容

```
'/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/'
```

###### ルールオブジェクトを使用する方法

Laravel 8 以降で推奨されている方法です。

> php artisan make:rule UniqueUsername

App\Rules\UniqueUsername.php というファイルが生成される

UniqueUsername.php

```php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueUsername implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // ここに独自のバリデーションロジックを実装する
        // 例: ユーザー名がデータベースに既に存在するかチェックする
        return User::where('username', $value)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been  
 taken.';
    }
}
```

</td></tr>
</table>

#### ルート設定を追加

routes\web.php を編集

```php
use App\Http\Controllers\PostController;
：
//  投稿入力画面
Route::get('post/create', [PostController::class,'create']);
//  投稿の書き込み
Route::post('post', [PostController::class,'store'])->name('post.store');
```

#### ブラウザで表示を確認

http://localhost/yoyaku/public/post/create

## 一覧表示画面の追加

#### routes\web.php に http://localhost/yoyaku/public/post/ への get アクセスを追加

PostController クラスの index() 関数を呼ぶという定義を追加

```
//  投稿一覧(インデックス)
Route::get('post', [PostController::class,'index']);
```

#### yoyaku\app\Http\Controllers\PostController.php に index() 関数を追加

view/post/ の index.blade.php を呼び出す定義を追加

```php
    //  投稿一覧(インデックス)を表示
    public function index() {
        $posts = Post::all();   //  Postデータの一括読み込み
        return view('post.index',compact('posts'));
    }
```

データの渡し方

<table border=1>
<tr><th> 渡し方</th><th >名前</th></tr>
<tr><td>['user' => $test]</td><td>変数で渡す。</td></tr>
<tr><td>->with('user', $test);</td><td>変数で渡す。</td></tr>
<tr><td>compact('user' , ...)</td><td>連想配列で渡す。</td></tr>
</table>

#### yoyaku\resources\views\post\index.blade.php に表示方法を定義

渡された $post は配列となっており、定義数分繰り返す

<details><summary>index.blade.php</summary>

```html
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">一覧表示</h2>
  </x-slot>
  <div class="mx-auto px-6">
    @foreach($posts as $post)
    <div class="mt-4 p-8 bg-white w-full rounded-2xl">
      <h1 class="p-4 text-lg font-semibold">{{$post->title}}</h1>
      <hr class="w-full" />
      <p class="mt-4 p-4">{{$post->body}}</p>
      <div class="p-4 text-sm font-semibold">
        <p>{{$post->created_at}}　/　 {{$post->user->name??'匿名'}}</p>
      </div>
    </div>
    @endforeach
  </div>
</x-app-layout>
```

</details>

#### ブラウザで表示を確認

http://localhost/yoyaku/public/post

#### 一覧表示に投稿者名の表示を追加

> \>php artisan make:migration add_user_id_column_to_users_table --table=posts

\database\migrations\2024_08_15_160354_add_user_id_column_to_users_table.php

```php
   public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //  postsテーブルに リレーション用の 'user_id' カラムを追加

            //  従来の方法
            // $table->unsignedBigInteger('user_id');       // user_id カラムを追加
            // $table->foreign('user_id')->references('id')->on('users');  // users テーブルの idとリレーション追加

            //  新たな方法
            $table->foreignId('user_id');
        });
    }
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
            $table->dropColumn('user_id');
        });
    }
```

> \> php artisan migrate

user_id の設定処理を追加
\app\Http\Controllers\PostController.php

```php
        //  user_id の設定を追加
        $validated['user_id'] = auth()->id();
```

\app\Models\Post.php

```php
    //  保存・更新したいカラムを設定
    //
    protected $fillable = [
        'title',
        'body',
        'user_id'   // 追加
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
```

\app\Models\User.php

```php
    public function posts() {
        //  1 対多のリレーションでは hasMany を使用
        return $this->hasMany(Post::class);

        //  1対1では hasOne を使う
    }
```

\resources\views\post\index.blade.php

```php
            <div class="p-4 text-sm font-semibold">
                <p>
                    {{$post->created_at}}　/　 {{$post->user->name??'匿名'}}
                </p>
            </div>
```

#### データの絞込

\app\Http\Controllers\PostController.php

```php
//  クエリビルダの使用・フォサード宣言
use Illuminate\Support\Facades\DB;
　：
class PostController extends Controller
{
　：
   public function index() {
        //$posts = Post::all();   //  Postデータの一括読み込み
        $posts = DB::table('posts')->get();     //  クエリビルダを使用した読み込み
        $posts = Post::with('user')->get();     //  Eagerロードを使用した読み込み
        return view('post.index',compact('posts'));
    }
```

Eloquent ORM を使用して where 条件を指定する

\app\Http\Controllers\PostController.php

```php
        $posts = Post::where('user_id',Auth()->id())->get();     //  自分の投稿だけ
        $posts = Post::where('user_id','!=',Auth()->id())->get();     //  自分の投稿以外
        $posts = Post::where('created_at','>=','2022-12-02')->get();     //  指定日付以降

```

## ミドルウェア

ミドルウェアとは、コントローラが実行される前後に配置する処理で、前処理ミドルウェアと後処理ミドルウェアと呼ぶ。

app\Http\Kernel.php

```php
class Kernel extends HttpKernel
{
　　　：
    protected $middleware = [
        ディフォルトで有効なミドルウェアを記述
        \App\Http\Middleware\TrimStrings::class,
    ];

    protected $middlewareGroups = [
        指定したグループで有効なミドルウェア
        'web' => [
            routes\web.php　で有効なミドルウェア
        ],

        'api' => [
            routes\api.php　で有効なミドルウェア
        ],
    ];

    protected $middlewareAliases = [
        設定が必要なミドルウェア
    ];
```

routes\web.php

```php
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');　　← 追加
```

#### 管理者のみがアクセス可能にする

###### role カラムを追加

> php artisan make:migration add_role_clumn_to_users_table --table=users

作成されたファイル
database\migrations\2024_08_20_153850_add_role_clumn_to_users_table.php

```php
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('name')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
```

###### MiddleWare の判定処理を追加

> php artisan make:middleware RoleMiddleware

app\Http\Middleware\RoleMiddleware.php が作成される

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->role == 'admin') {
            //  ログインユーザの role が 'admin' ならリクエストを実行
            return $next($request);
        }
        return redirect()->route(('dashboard'));
    }
}
```

###### Middleware の登録

app\Http\Kernel.php

```php
    protected $middlewareAliases = [
　　　　　　：
        'admin' => \App\Http\Middleware\RoleMiddleware::class,
　　　　　　：
```

#### Middleware によるアクセス制限の記述を追加

routes\web.php 　に Middleware('admin') を追加

> Route::get('reserve/create', [ReserveController::class,'create'])->name('reserve.create')<b><font color='red'>->Middleware('admin');</font></b>

## データ変更画面の追加

#### route 設定

routes\web.php 　に /edit/ を追加

> Route::get('reserve/edit/{reserve}', [ReserveController::class,'edit'])->name('reserve.edit');

{reserve} には呼び出し元画面で読み込まれている JSON 定義が渡されてくる

#### contloer 設定

引数を 'reserve' として View に渡す

```php
    public function edit(Reserve $reserve) {
        //return view("Reserve.RUpdate",compact('reserve'));
        return view("Reserve.REdit",compact('reserve'));
    }
```

#### Model に日付フィールドであることを定義する

これを行わないと、全て文字列型の yyyy/mm/dd となる。
View で日付項目に初期値として設定するためには　 yyyy-mm-dd 形式とする必要がある

```php
    protected $casts = [
        'ReqDate' => 'datetime',
        'ReserveDate' => 'datetime',
    ];
```

#### view 設定

resources\views\Reserve\REdit.blade.php

```html
<form action="/sample/index" method="post">
    @method('patch')
    @csrf
    <label for="ClitNameKanji">テキストボックス:</label>
    <input type="text" id="ClitNameKanji" name="ClitNameKanji" required
        value="{{ old('ClitNameKanji',$reserve->ClitNameKanji) }}"><br>

    <label for="reservation_date">日時:</label>
    <input type="datetime-local" id="ReserveDate" name="ReserveDate" required
            value="{{ old('ReserveDate',$reserve->ReserveDate) }}"><br>

    <label for="CliResvType">ドロップダウンリスト :</label>
    <select id="CliResvType" name="CliResvType" required>
        <option value="1" {{ old('field_name', $reserve->CliResvType) == '1' ? 'selected' : '' }}>タイプ1</option>
        <option value="2" {{ old('field_name', $reserve->CliResvType) == '2' ? 'selected' : '' }}>タイプ2</option>
    </select><br>


    　：
    <input type="submit" text="提出">
</form>
```

@method('patch')と書くと、以下の html が追加される

```html
<input type="hidden" name="_method" value="patch" />
```

###### old()関数を使い、変更前の値を表示させる

テキストボックスの場合

> value="{{ old('ClitNameKanji',$reserve->ClitNameKanji) }}"

## Inertia「イナーシャ」とは

Inertia とはサーバーサイド（バックエンド）とクライアントサイド（フロントエンド）をつなぐためのツール
今回でいうとクライアントサイド（Vue.js）とサーバーサイド（Laravel）に当たります。

Inertia を使えば、Vue 側でも、laravel のルート設定を使ったり、ヘッダを組み込んだり、ページネーションを簡単に搭載したりできます。

#### Inertia の追加

> composer require inertiajs/inertia-laravel

vendor/inertiajs/inertia-laravel ディレクトリが作成されます。

<ul>
<li>src/Inertia.php: Inertia ファサードの実装が含まれています。このファイルには、render()、location()、redirect() などのメソッドが定義されています。</li>
<li>use Inertia\Inertia; という記述は、この Inertia ファサードをアプリケーション内で使用できるようにするためのものです。ファサードを使用することで、Inertia クラスのインスタンスを直接生成せずに、静的なメソッドを呼び出すことができます。</li>
</ul>

コントローラ

```php
    public function index()
    {
        $items = Item::select('id', 'name', 'price', 'is_selling')->get();

        //Inertia::renderという関数は第一引数にコンポーネント、第二引数にプロパティ配列を渡します。
        //Inertia::renderを呼び出す事で、どのコンポーネントに対してどの変数をセットするかをここできめ、vueにも反映させることができます。
        return Inertia::render('Items/Index', [
            'items' => $items
        ]);
    }
```

## コンポーネントの追加

コンポーネントとは WEB ページの一部として使うテンプレート

コンポーネントを追加するには以下のコマンドを入力する

> php artisan make:component Message

コマンドを実行すると、以下２つのファイルが作成される

<li>コンポーネント  ：yoyaku\app\View\Components\Message.php</li>
<li>View           : yoyaku\resources\views\components\message.blade.php</li>

#### メッセージを表示するコンポーネント

<ul>
yoyaku\app\View\Components\Message.php

```php
<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Message extends Component
{
    public $message;    //　←メンバ変数を追加

    public function __construct($message)
    {   //  コンストラクタで表示するメッセージ文を受け取る
        $this->message = $message;
    }

    public function render(): View|Closure|string
    {
        return view('components.message');
    }
}
```

#### View

yoyaku\resources\views\components\message.blade.php

```php
@if($message)
<div class="p-4 m-2 rounded bg-green-100">
    {{ $message }}
</div>
@endif
```

#### 使用する View ファイルに以下の定義を追加する

```php
        <x-message :message="session('message')" />
```

コンポーネントを呼び出す blade ファイルでは、タグ形式でコンポーネントを呼び出します。
タグ名はコンポーネント名をケバブケースにして頭に「x-」をつけたものになります。
（例：コンポーネント名が ComponentTest の場合、タグ名は x-component-test）

パラメータは属性として渡しますが、属性名もケバブケースで表現します。
（例：コンポーネント側のパラメータ名が testTitle の場合、属性名は test-title）

###### コンポーネントの呼び出し方法

コンポーネント名を RTextbox として追加する

> php artisan make:component RTextbox

```
コンポーネントクラス
app\View\Components\RTextbox.php
```

```
Viewファイル
resources\views\components\r-textbox.blade.php
```

```php
クラス名の1,2文字目
①    <x-rTextbox  inputName="ClitNameKanji">氏名（漢字）</x-rTextbox>
②    <x-rTextbox :inputName="ClitNameKanji">氏名（漢字）</x-rTextbox>
③    <x-rTextbox :inputName="$ClitNameKanji">氏名（漢字）</x-rTextbox>
```

① のように属性名にコロンが付いていない場合にはパラメータには文字列の ClitNameKanji が渡される。
② のようにコロンを付けた場合には固定数の ClitNameKanji が渡される。
③ のようにコロンを付けた場合には、コントローラから渡された変数の$ClitNameKanji が渡される。

本日日付など、関数を呼び出したい場合もコロン付きとする

```php
                <x-rtextbox name="ReserveDate" type="datetime-local"
                    :value="date('Y-m-d 07:00')" required>予約日:</x-rtextbox>
```

#### エラー「Unresolvable dependency resolving ･･･」が表示される場合

> Unresolvable dependency resolving [Parameter #0 [ <required> $name ]] in class App\View\Components\Alert (View: /...path.../resources/views/welcome.blade.php)

ブラウザで「キャッシュの消去と再読み込み」を行うと解消される

</ul>

## イベントの追加

> php artisan make:event ChatMessageRecieved

yoyaku\app\Events\ChatMessageRecieved.php

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatMessageRecieved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $message;
    protected $request;

    /*  Create a new event instance.*/
    public function __construct($request) {
        $this->request = $request;
    }
    /*  イベントをブロードキャストすべき、チャンネルの取得*/
    public function broadcastOn() {
         return new Channel('chat');
    }
    /*  ブロードキャストするデータを取得    */
    public function broadcastWith() {
         return [
            'message' => $this->request['message'],
            'send' => $this->request['send'],
            'recieve' => $this->request['recieve'],
        ];
    }
     /*  イベントブロードキャスト名*/
    public function broadcastAs() {
         return 'chat_event';
    }
}
```

## helper 関数の追加

ヘルパー関数ファイルを追加
yoyaku\app\helpers.php

ヘルパー関数としてファイルを登録
yoyaku\composer.json

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [                  //  ← 追加
        "app/helpers.php"       //  ← 追加
    ]                           //  ← 追加
},
```

定義を追加したら、これを反映するために以下のコマンドを入力

> php artisan config:cache

## ajax の組み込み。

#### ajax を使えるようにするために、app.blade.php の head の部分でスクリプトを読み込む。

\resources\views\layouts\app.blade.php

```php
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
```

#### Ajax のスクリプトを作成

フォルダ「\public\js」を作成

public/js/comment.js

```javascript
$(function () {
  get_data();
});

function get_data() {
  //  5秒ごとに、jsonデータを取得
  $.ajax({
    url: "result/ajax/",
    dataType: "json",
    success: (data) => {
      console.log(data);
    },
    error: () => {
      alert("ajax Error");
    },
  });

  setTimeout("get_data()", 5000);
}
```

## 郵便番号から住所を自動入力する方法

Composer を使ってパッケージをインストール

> composer require SUKOHI/laravel-jp-postal-code

## ポップアップ画面の追加

Composer を使って Laravel プロジェクトに Livewire をインストールします。

> composer require livewire/livewire

Livewire のビュー、スタイルシート、JavaScript ファイルをパブリッシュします。

> php artisan livewire:publish

モーダルコンポーネントの作成

> php artisan make:livewire ShopClosedModal

以下の php ファイルが作成される
app\Livewire\ShopClosedModal.php
resources/views/livewire/modal.blade.php

コントローラの作成

> php artisan make:controller ShopClosedModalController

## プロジェクトに Bootstrap を適用するまでの手順

#### laravel/ui のパッケージをインストール

> composer require laravel/ui

#### Bootstrap をインストール

> php artisan ui bootstrap

#### プラグインのインストール

※ロリポップでは使えない

> npm install

#### blade ファイルに Bootstrap を適用

```html
<!DOCTYPE html>
<html lang="ja">
  <head>
    ・・・ @vite(['resources/sass/app.scss', 'resources/js/app.js']) ・・・
  </head>
</html>
```

Laravel10 では、Laravel Mix ではなく Vite を使用しているため、public/css/app.css にスタイルが出力されない
したがって、blade ファイルでは resources/sass/app.scss のファイルパスを指定している

#### Vite の実行

> npm run dev

プロジェクトに反映

> composer dump-autoload

                        value="{{ old('ReserveDate',"<?php echo substr($reserve->ReserveDate,0,10); ?>") }}"><br>
                        value="<?php echo substr($reserve->ReserveDate,0,10); ?>"><br>

           <form method="post" action="{{ isset($reserve) ?? route('reserve.store') :: route('reserve.update') }}">

vendor\illuminate\collections\helpers.php

date('Y-m-d', strtotime('-1 month'));

                    :value="<?php echo date('Y-m-d 07:00');?>" required>予約日:</x-rtextbox>
                    value="2024-09-23 07:00" required>予約日:</x-rtextbox>

                <x-rtextbox name="ReserveDate" type="datetime-local"
                    :value="date('Y-m-d 07:00')" required>予約日:</x-rtextbox>



    <!-- button id="downloadBtn">HTMLをダウンロード</button>

    <script>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            // ページ全体のHTMLを取得
            let html = document.documentElement.outerHTML;

            // ダウンロード用のリンクを作成
            let blob = new Blob([html], { type: 'text/html' });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'page.html';
            link.click();
        });
    </script -->



                                <td class="sun">{{ $week[0]['day'] }}
                                    @isset($week[0]['totalCnt'])
                                        <span class="ntotal">{{ $week[0]['totalCnt'] }}</span>
                                    @endisset
                                </td>
                                @for($ix=1;$ix<6;$ix++)
                                    <td class="other">{{ $week[$ix]['day'] }}
                                    @isset($week[$ix]['totalCnt'])
                                        <span class="ntotal">{{ $week[$ix]['totalCnt'] }}</span>
                                    @endisset
                                    </td>
                                @endfor
                                <td class="sat">{{ $week[$ix]['day'] }}
                                    @isset($week[$ix]['totalCnt'])
                                        <span class="ntotal">{{ $week[$ix]['totalCnt'] }}</span>
                                    @endisset
                                </td>


                <x-rTextbox name="ReserveDate" type="datetime-local"
                    value="{{old('ReserveDate', $destdate) }}" required>予約日:</x-rTextbox>

Symfony\Component\HttpFoundation\Response->send (c:\Tools\AnHttpd\nmaki\yoyaku\vendor\symfony\http-foundation\Response.php:423)
{main} (c:\Tools\AnHttpd\nmaki\yoyaku\public\index.php:53)

## ビューコンポーザー

Laravel のビューコンポーザーは、特定のビューまたはビューグループにデータを自動的にバインドするための仕組みです。これにより、コントローラーでビューにデータを渡す処理を減らし、コードの整理や再利用性を高めることができます。

#### ビューコンポーザーの利点:

コードの整理: ビューに渡すデータをコントローラーから分離し、ビューコンポーザーにまとめることで、コントローラーのコードを簡潔に保つことができます。
再利用性の向上: 複数のビューで共通のデータを渡す場合に、ビューコンポーザーを一度定義するだけで済みます。
テストの容易性: ビューコンポーザーは、ビューにデータを渡す処理を独立したクラスとして定義するため、テストが容易になります。

Laravel 8.0 以降では、ビューコンポーザーのクラスを生成するための専用の Artisan コマンドは提供されなくなりました。代わりに、通常のクラスとして作成し、手動で app/Providers/AppServiceProvider.php に登録する必要があります。

ビューコンポーザーのクラスを手動で作成:

app/View/Composers ディレクトリを作成し、その中に SelShopComposer.php ファイルを作成します。
