# display flex のまとめ
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

Flexboxレイアウトを使用する場合には、CSSファイルで親要素（コンテナ）にdisplay:flexを指定する。これを指定するのは、親要素のコンテナのみ。

```css
.f-container{
  display:flex; 
}
```

Flexboxで細かくレイアウトを調整する時には、コンテナとアイテムにそれぞれプロパティを指定して調整を行う。

### コンテナに使用できるプロパティ

<table border=2>
<tr><th colspan=2>コンテナに使用できるプロパティ</th><th>説明</th><th>表示例</th></tr>
<tr><td rowspan=4>コンテナ<br>(親要素、f-container)</td>
    <td rowspan=4>flex-direction<br>アイテムの並び順を指定する</td>
            <td>row ※<br>アイテムを水平方向に左から右へと配置<br><ul>①②③④⑤⑥</ul></td></tr>
        <tr><td>row-reverse<br>アイテムを水平方向に右から左へと配置<br><ul>⑥⑤④③②①</ul></td></tr>
        <tr><td>column<br>アイテムを垂直方向に上から下へと配置<br><ul>①<br>②<br>③<br>④<br>⑤<br>⑥</ul></td></tr>
        <tr><td>colmn-reverse<br>アイテムを垂直方向に下から上へと配置<br><ul>⑥<br>⑤<br>④<br>③<br>②<br>①</ul></td></tr>
    <tr><td rowspan=3></td><td rowspan=3>flex-wrap<br>アイテムの折り返しを指定する</td>
            <td>>nowrap ※<br>アイテムを折り返さずに一列に配置<ul>①②③④⑤⑥</ul></td></tr>
        <tr><td>wrap複数行のアイテムを上から下へと配置<br><ul>①②③<br>④⑤⑥</ul></td></tr>
        <tr><td>wrap-reverse<br>複数行のアイテムを下から上へと配置<ul>④⑤⑥<br>①②③</ul></td></tr>
    <tr><td rowspan=5></td><td rowspan=5>flex-flow<br>アイテムの並び順と折り返しを一括で指定する</td>
            <td>flex-start ※<br>アイテムを左揃えで配置<br><ul></ul></td></tr>
        <tr><td>flex-end<br>アイテムを右揃えで配置<br><ul></ul></td></tr>
        <tr><td>center<br>アイテムを左右中央揃えで配置<br><ul></ul></td></tr>
        <tr><td>space-between<br>両端のアイテムを余白を空けずに配置し、他の要素は均等に間隔を空けて配置<br><ul></ul></td></tr>
        <tr><td>space-around<br>両端のアイテムも含めて、均等な間隔を空けて配置<br><ul></ul></td></tr>
    <tr><td rowspan=5></td><td rowspan=5>justify-content<br>アイテムの水平方向の位置を指定する</td>
            <td>stretch<br>アイテムを上下の余白を埋めるように配置<br><ul></ul></td></tr>
        <tr><td>flex-start<br>アイテムを上揃えで配置<br><ul></ul></td></tr>
        <tr><td>flex-end<br>アイテムを下揃えで配置<br><ul></ul></td></tr>
        <tr><td>center<br>アイテムを上下中央揃えで配置<br><ul></ul></td></tr>
        <tr><td>baseline<br>アイテムをベースラインに合わせて配置<br><ul></ul></td></tr>
    <tr><td rowspan=5></td><td rowspan=5>align-items<br>アイテムの垂直方向の位置を指定する</td>
            <td>stretch<br>アイテムの行を余白を埋めるように配置<br><ul></ul></td></tr>
        <tr><td>flex-start<br>アイテムの行を上揃えで配置<br><ul></ul></td></tr>
        <tr><td>flex-end<br>アイテムの行を下揃えで配置<br><ul></ul></td></tr>
        <tr><td>center<br>アイテムを上下中央揃えで配置<br><ul></ul></td></tr>
        <tr><td>baseline<br>アイテムをベースラインに合わせて配置<br><ul></ul></td></tr>
    <tr><td rowspan=6></td><td rowspan=6>align-content<br>アイテムの行の垂直方向の位置を指定する</td>
            <td>stretch<br>アイテムの行を余白を埋めるように配置<br><ul></ul></td></tr>
        <tr><td>flex-start<br>アイテムの行を上揃えで配置<br><ul></ul></td></tr>
        <tr><td>flex-end<br>アイテムの行を下揃えで配置<br><ul></ul></td></tr>
        <tr><td>center<br>アイテムの行を上下中央揃えで配置<br><ul></ul></td></tr>
        <tr><td>space-between<br>最上行と最下行のアイテムを余白を空けずに配置し、他のアイテム行は均等に間隔を空けて配置<br><ul></ul></td></tr>
        <tr><td>space-around<br>最上行と最下行のアイテムを余白を空けずに配置し、他のアイテム行は均等に間隔を空けて配置<br><ul></ul></td></tr>
</table>

        <td></td><td><br><ul></ul></td></tr>

### アイテムに使用できるプロパティ

<table border=2>
<tr><th colspan=2>アイテムに使用できるプロパティ</th><th>説明</th></tr>
<tr><td rowspan=4>コンテナ<br>(親要素、f-container)</td>
    <tr><td rowspan=1>order<br>アイテムの並び順を指定<br>order: 順番</td>
            <td>.f-item01 {order: 3;} <br>
                .f-item02 {order: 1;} <br>
                .f-item03 {order: 4;} <br>
                .f-item04 {order: 2;} <br><ul></ul></td></tr>
    <tr><td rowspan=1>flex-grow<br>アイテムの大きさと伸び率を指定<br>flex-grow: 大きさ1～</td>
            <td>.f-item01 {flex-grow:3;}  <br>
                .f-item02 {flex-grow:1;}  <br>
                .f-item03 {flex-grow:4;}  <br>
                .f-item04 {flex-grow:2;} <br><ul></ul></td></tr>
    <tr><td rowspan=1>flex-shrink<br>アイテムの縮み率を指定<br>flex-shrink: 大きさ1～</td>
            <td>.f-item01 {flex-shrink:1;}  <br>
                .f-item02 {flex-shrink:2;}  <br>
                .f-item03 {flex-shrink:1.5;}  <br>
                .f-item04 {flex-shrink:1;} <br><ul></ul></td></tr>
    <tr><td></td><td rowspan=1>flex-basis<br>アイテムのベースの幅を指定<br>flex-basis: 幅%</td>
            <td>.f-item01 {flex-basis:50%;} <br>
                .f-item02 {flex-basis:20%;} <br>
                .f-item03 {flex-basis:30%;} <br>
                .f-item04 {flex-basis:40%;}<br><ul></ul></td></tr>
    <tr><td></td><td rowspan=1>flex<br>アイテムの伸び率、縮み率、ベースの幅を一括指定<br>flex: 伸び率 縮み率 ベース幅%</td>
            <td>.f-item01 {flex: 1 0 20%;} <br>
                .f-item02 {flex: 2 0 30%;} <br>
                .f-item03 {flex: 1 0 50%;}<br><ul></ul></td></tr>
    <tr><td></td><td rowspan=6>align-self<br>アイテムの垂直方向の位置を指定</td>
<style>
.f-item01 {align-self: flex-start;} 
.f-item02 {align-self: flex-end;} 
.f-item03 {align-self: center;} 
.f-item04 {align-self: baseline;} 
.f-item05 {align-self: stretch;}
</style>
                     <td>auto<br>親要素のalign-itemsの値を使用<br><ul></ul></td></tr>
        <tr><td></td><td>flex-start<br>アイテムを上揃えで配置<br><ul></ul></td></tr>
        <tr><td></td><td>flex-end<br>アイテムを下揃えで配置<br><ul></ul></td></tr>
        <tr><td></td><td>center<br>アイテムを中央揃えで配置<br><ul></ul></td></tr>
        <tr><td></td><td>baselne<br>アイテムをベースラインに合わせて配置<br><ul></ul></td></tr>
        <tr><td></td><td>stretch<br>アイテムを上下の余白を埋めるように配置<br><ul></ul></td></tr>
        <tr><td colspan=3><ul class="f-container">
            <div class="blueBox f-item01">flex-start</div>
            <div class="blueBox f-item02">flex-end</div>
            <div class="blueBox f-item03">center</div>
            <div class="blueBox f-item04">baseline</div>
            <div class="blueBox f-item05">stretch</div>
        </ul></td></tr>
</table>

