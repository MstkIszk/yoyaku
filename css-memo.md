
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

# displayプロパティ

<table border=2>
<tr><th>プロパティ値</th><th>説明</th><th>表示例</th></tr>
<tr><td>block※</td><td>縦並び<br>width、height、padding、marginなど幅や高さ、余白を設定できる</td></tr>
<tr><td>inline</td><td>横並び<br>幅と高さは指定できないが、左右の余白のみ調節ができる</td></tr>
<tr><td>inline-block</td><td>横並びだが、blockと同じような性質 </td></tr>
<tr><td>none</td><td>非表示</td></tr>
</table>

	
	
	


# positionプロパティ

<table border=2>
<tr><th>プロパティ値</th><th>説明</th><th>表示例</th></tr>
<tr><td>static※</td><td>要素は通常のドキュメントフローに従って配置</td></tr>
<tr><td>relative</td><td> 相対的位置指定。要素の元の位置を基準にして、top、right、bottom、leftプロパティで移動可</td></tr>
<tr><td>absolute</td><td>絶対的位置指定。最近のパラメータを持つ最初の祖先要素を基準にして、top、right、bottom、leftプロパティで移動できます。</td></tr>
<tr><td>fixed</td><td>固定的位置指定。viewport（ビューポート）を基準にして、スクロールしても位置が固定されます。</td></tr>
<tr><td>sticky</td><td>相対的な位置と固定的な位置を組み合わせたような動作。要素がビューポート内に表示されている間は相対的に動き、ビューポートの外に出ると固定されます。</td></tr>
</table>
