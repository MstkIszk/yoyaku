<style>
    .clear{
      clear: both;
    }
    .left{
      float: left;
      margin-bottom:10px;
    }
   .box1{
      width:200px;
      height:200px;
      background-color: white;
      border: solid;
      padding: 10px;
    }
    .box2{
      width:420px;
      height:200px;
      background-color: rgb(180, 200, 220);
      border: solid;
      padding:10px;
    }
    .box3{
      width:200px;
      height:100px;
      background-color: rgb(255, 81, 0);
    }
    .guide{
      width:200px;
      height:100px;
    }
</style>

# SVGタグ
SVGタグはこれから描画する図形のキャンバスのようなもので、通常widthやheight属性を使ってサイズを指定します。

<div class="clear">

## 1. rectタグ
rectタグは、四角形を描画する。

<div class="left box1"><svg>

<rect x="50" y="20" rx="20" ry="20" width="80" height="60" style="fill:red;stroke:black;stroke-width:5;opacity:0.5" />
</svg></div>
<div class="left box2">
x、y属性については、それぞれ左と上からの開始位置を指定し、rxとry属性は角をどれくらい丸くするかを指定しています。<br>

```html
<rect x="50" y="20" rx="20" ry="20" width="80" height="60" style="fill:red;
stroke:black;stroke-width:5;opacity:0.5" />
```
</div></div>

<div class="clear">

## 2. circleタグ
<div class="left box1"><svg>
<circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" />
</svg>

</svg></div>
<div class="left box2">
circleタグは、円を描画するための命令です。

例えば、半径40、中心座標(x, y)＝(50, 50)の円を描画するには、以下のようなタグを利用します。

```html
<circle cx="50" cy="50" r="40" stroke="black" 
stroke-width="3" fill="red" />
```
</div></div>

<div class="clear">

## 3. ellipseタグ

<div class="left box1"><svg>
<ellipse cx="90" cy="50" rx="80" ry="50" style="fill:yellow;stroke:purple;stroke-width:2" />
</svg></div>
<div class="left box2">
ellipseタグは、楕円を描画するための命令です。
基本的には、circleタグと同じように描画することができますが、rx、ryタグで横半径・縦半径を指定することができます。

```html
<ellipse cx="90" cy="50" rx="80" ry="50" 
style="fill:yellow;stroke:purple;stroke-width:2" />
```
</div></div>



<div class="clear">

## 4. lineタグ
<div class="left box1"><svg>
<line x1="0" y1="0" x2="180" y2="90" style="stroke:rgb(255,0,0);stroke-width:2" />
</svg></div>
<div class="left box2">
lineタグは、線を描画するための命令です。
x1・y1タグで開始座標を指定し、x2・y2タグで終了座標を指定します。

```html
<line x1="0" y1="0" x2="180" y2="90" 
style="stroke:rgb(255,0,0);stroke-width:2" />
```
</div></div>

<div class="clear">

## 5. polygonタグ
<div class="left box1"><svg>
<polygon points="100,10 40,98 190,48 10,48 160,98" style="fill:lime;stroke:purple;stroke-width:5;fill-rule:nonzero;" />
</svg></div>
<div class="left box2">
polygonタグは、多角形を描画するための命令です。
pointsでは、x座標とy座標をカンマで区切って指定し、そのセットを半角スペースで区切っていきます。それらの点をつなぎ合わせて、多角形を作っていきます。

```html
<polygon points="100,10 40,98 190,48 10,48 160,98" 
style="fill:lime;stroke:purple;stroke-width:5;fill-rule:nonzero;" />
```
</div></div>


<div class="clear">

## 6. polylineタグ


<div class="left box1"><svg>
<polyline points="0,20 20,20 20,40 40,40 40,60 60,60 60,80" style="fill:white;stroke:red;stroke-width:4" />
</svg></div>
<div class="left box2">
polylineタグはpolygonタグ同様に線を結びますが、始点と終点は結ばないので、線のようになります。

```html
<polyline points="0,20 20,20 20,40 40,40 40,60 60,60 60,80" 
style="fill:white;stroke:red;stroke-width:4" />
```
</div></div>

<div class="clear">

## 7. pathタグ

<div class="left box1"><svg>
<path d="M50 0 L20 90 L150 90 Z" />
</svg></div>
<div class="left box2">
pathタグは、SVGの中でも最も複雑な図形を描画できる図形で、d属性の中にパスデータを記述してく。数値の前に指定されたアルファベットの「コマンド」と言われる識別子を指定できます。<br>
Z:現在の点から現在の部分パスの始点まで直線を描き、部分パスを閉じる<br>
S:現在の点から点 (x, y) へ三次ベジェ曲線を描く
大文字で絶対座標、小文字で相対座標が続きます。

```html
<path d="M50 0 L20 90 L150 90 Z" />
```
</div></div>

<div class="clear">

## 8. textタグ

<div class="left box1"><svg>
<text x="0" y="15" fill="#0060e6" transform="rotate(30 20,40)">I love ferret!</text>
</svg></div>
<div class="left box2">
textタグは、テキストを挿入する命令です。
パスデータではなく、テキストをそのまま乗せている形であり、アウトライン化はされていません。

```html
<text x="0" y="15" fill="#0060e6" transform="rotate(30 20,40)">I love ferret!</text>
</svg></div>
```
</div></div>

<div class="clear">

# SVGを扱うためのCSSプロパティ


## 1. fill (塗り)
fillは、要素の中の塗りの色を指定することができるプロパティです。
もちろん、塗りは単色だけでなく、グラデーションを指定することもできます。

fill: #0060e6;


## 2. fill-opacity (塗りの透明度)
fill-opacityは、fillで塗られた箇所の透明度を変えることができるプロパティです。


fill-opacity: 0.6;
ちなみにopacityでも透明度を変えることができますが、opacityはSVG全体の透明度を調整するので、線も含めて透過してしまいます。

## 3. stroke (線)
strokeは、要素のアウトラインの色を指定できるプロパティです。


stroke: #0060e6;
borderなどの他のアウトライン関連のプロパティと違い、幅や大きさなどをショートハンドで記述することはできません。

## 4. stroke-opacity (線の透明度)
stroke-opacityは、要素のアウトラインの透明度を指定できるプロパティです。


stroke-opacity: 0.4;
## 5. stroke-width (線の太さ)
stroke-widthは、外形線の幅を指定できるプロパティです。
0かnoneを指定すると、アウトラインを消すことができます。


stroke-width: 3;
## 6. stroke-linecap (線の先端の形)
stroke-linecapは、パスの先端の形を指定するプロパティです。
「butt」で線に等しく平らかな形になり、「square」で線の端が四角くなり、「round」で線の端が丸くなります。


stroke-linecap: square;
## 7. stroke-linejoin (線のつなぎ目の形)
stroke-linejoinは、パスのつなぎ目の形を指定するプロパティです。
「miter」はそのままつなぎ合わせ、「bevel」はつなぎ目が平らになり、「round」はつなぎ目が丸くなります。


stroke-linejoin: bevel;
## 8. stroke-dasharray (破線の間隔)
stroke-dasharrayは、破線の間隔を指定するプロパティです。
指定した間隔に応じて、等間隔で破線を表示します。


stroke-dasharray: 3px;
## 9. stroke-dashoffset (破線の開始位置)

stroke-dashoffsetは、破線の開始位置を指定するプロパティです。

stroke-dashoffset: 10px;

# SVGをアニメーションで動かすには
SVGを動かすにはCSSアニメーションやJavaScript、jQueryなどを使う方法がありますが、もっとも簡単な方法は「@keyframes」でアニメーションのセットを作成し、pathやcircleといったSVGの構成要素に「animation」プロパティを指定することです。

例えば、pathにアニメーションを指定するには、以下のように指定していきます。

```css
path{
　　animation: pathAnimation ease-in-out 3s;
}

@keyframes pathAnimation{
　　from{
　　　　stroke-dashoffset: 100;
　　　　fill-opacity: 0;
　　}
　　to{
　　　　stroke-dashoffset: 0;
　　　　fill-opacity: 1;
　　}
}
```

stroke-dasharrayには線がすべてつながるまでの値を指定していくのがポイントです。
上記の例はあくまでもシンプルな形で表現したアニメーションですが、プロパティを加えていったり「@keyframes」のタイミングを動かすことで複雑なアニメーションを実装することもできるようになります。
