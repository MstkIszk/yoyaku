<style>
    .code-example {
        padding: 10px;
        border: 1px solid #ccc; /* 枠線 */
        box-shadow: inset 2px 2px 5px #aaa, /* 内側の影（右下） */
                    inset -2px -2px 5px #fff; /* 内側の影（左上） */
    }
</style>

<figure class="table-container"><table class="no-markdown">
  <colgroup>
    <col>
    <col style="width: 50%">
    <col>
  </colgroup>
  <thead>
    <tr>
      <th>型</th><th>説明</th><th>基本的な例</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/button">button</a></td>
      <td>
        プッシュボタンで、既定の動作を持たず、<a href="#value"><code>value</code></a> 属性の値（既定では空）を表示します。
      </td>
      <td class="code-example">
            <input type="button" value="button">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/checkbox">checkbox</a></td>
      <td>チェックボックスで、選択または未選択のうちひとつの値をとることができます。</td>
      <td class="code-example">
            <input type="checkbox">checkbox</input>
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/color">color</a></td>
      <td>
        色を指定するためのコントロールです。対応しているブラウザーでは、アクティブになったときにカラーピッカーが開きます。
      </td>
      <td class="code-example">
            <input type="color">color</input>
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/date">date</a></td>
      <td>
        日付（時刻を除いた年、月、日）を入力するためのコントロールです。
        対応しているブラウザーでは、アクティブになったときに日付ピッカーまたは年月日の数値のホイールが開きます。
      </td>
      <td class="code-example">
            <input type="date" id="date" name="date" value="2025-04-12">
      </td>
    </tr>
    <tr>
      <td>
        <a href="/ja/docs/Web/HTML/Reference/Elements/input/datetime-local">datetime-local</a>
      </td>
      <td>
        タイムゾーン情報がない日付と時刻を入力するためのコントロールです。
        対応しているブラウザーでは、アクティブになったときに日付ピッカーまたは日付および時刻部分の数値のホイールが開きます。
      </td>
      <td class="code-example">
            <input type="datetime-local" id="date" name="date" value="2025-04-12 10:20:30">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/email">email</a></td>
      <td>
        電子メールアドレスを編集するための欄です。<code>text</code> 入力欄のように見えますが、対応しているブラウザーや動的なキーボードのある端末では、入力値を検証したり、関連するキーボードを表示したりします。
      </td>
      <td class="code-example">
            <input type="email" value="aaa@bb.com">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/file">file</a></td>
      <td>
        ユーザーがファイルを選択するコントロールです。<a href="#accept"><code>accept</code></a> 属性を使用して、コントロールが選択することができるファイル形式を定義することができます。
      </td>
      <td class="code-example">
            <input type="file">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/hidden">hidden</a></td>
      <td>
        表示されないコントロールですが、その値はサーバーに送信されます。隣の列には例がありますが、非表示です。
      </td>
      <td class="code-example">
            <input type="hidden">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/image">image</a></td>
      <td>
        グラフィックの <code>submit</code> ボタンです。<code>src</code> 属性で定義された画像を表示します。<a href="#alt"><code>alt</code></a> 属性は <a href="#src"><code>src</code></a> の画像が見つからないときに表示されます。
      </td>
      <td class="code-example">
            <input type="image" src="C:\Tools\AnHttpd\nmaki\yoyaku\public\images\pagetile.jpg">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/month">month</a></td>
      <td>タイムゾーン情報がない年と月を入力するためのコントロールです。</td>
      <td class="code-example">
            <input type="month" value="2025-10">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/number">number</a></td>
      <td>
        数値を入力するためのコントロールです。スピナーを表示し、既定の検証を追加します。動的キーボードを持つ一部の端末では、テンキーを表示します。
      </td>
      <td class="code-example">
            <input type="number" value="123">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/password">password</a></td>
      <td>
        入力値を隠す単一行テキストフィールド。サイトが安全ではない場合はユーザーに警告します。
      </td>
      <td class="code-example">
            <input type="password" value="123">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/radio">radio</a></td>
      <td>
        ラジオボタンで、同じ <a href="#name"><code>name</code></a> の値を持つ複数の選択肢から一つの値を選択することができます。
      </td>
      <td class="code-example">
            <input type="radio">radio</input>
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/range">range</a></td>
      <td>
        厳密な値であることが重要ではない数値を入力するためのコントロールです。範囲のウィジェットを表示し、既定では中央の値になります。
        <a href="#min"><code>min</code></a> と <a href="#max"><code>max</code></a> の組み合わせで、受け入れる値の範囲を定義することができます。
      </td>
      <td class="code-example">
            <input type="range" value=20>
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/reset">reset</a></td>
      <td>
        フォームのコントロールを既定値に初期化するボタンです。推奨しません。
      </td>
      <td class="code-example">
            <input type="reset" value=reset>
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/search">search</a></td>
      <td>
        検索文字列を入力するための単一行のテキスト欄です。入力値から改行が自動的に取り除かれます。対応しているブラウザーでは、入力欄を初期化するための削除アイコンが表示されることがあり、欄の内容を消去するために使用することができます。動的なキーパッドを持つ一部の端末では、Enter キーの代わりに検索アイコンを表示します。
      </td>
      <td class="code-example">
            <input type="search" value=search>
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/submit">submit</a></td>
      <td>フォームを送信するボタンです。</td>
      <td class="code-example">
            <input type="submit" value="送信">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/tel">tel</a></td>
      <td>
        電話番号を入力するためのコントロールです。動的なテンキーを備えた一部の機器では、電話用のテンキーを表示します。
      </td>
      <td class="code-example">
            <input type="tel" value="090-1234-5678">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/text">text</a></td>
      <td>
        既定値です。単一行のテキスト入力欄です。改行は自動的に入力値から取り除かれます。
      </td>
      <td class="code-example">
            <input type="text" value="text">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/time">time</a></td>
      <td>タイムゾーン情報がない時刻を入力するためのコントロールです。</td>
      <td class="code-example">
            <input type="time" value="10:20:30">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/url">url</a></td>
      <td>
        URL を入力するための入力欄です。<code>text</code> 入力欄のように見えますが、対応しているブラウザーや動的なキーボードのある端末では、入力値を検証したり、関連するキーボードを表示したりします。
      </td>
      <td class="code-example">
            <input type="url" value="aaa.com">
      </td>
    </tr>
    <tr>
      <td><a href="/ja/docs/Web/HTML/Reference/Elements/input/week">week</a></td>
      <td>
        年と週番号で構成される日付を入力するためのコントロールです。週番号はタイムゾーンを伴いません。
      </td>
      <td class="code-example">
            <input type="week" value="2025-W15" />
      </td>
    </tr>
    <tr>
      <th colspan="3">廃止された値</th>
    </tr>
    <tr>
      <td><code>datetime</code> <abbr class="icon icon-deprecated" title="Deprecated. Not for use in new websites.">
<span class="visually-hidden">非推奨;</span>
</abbr></td>
      <td>
        UTC タイムゾーンに基づく日付と時刻 (時、分、秒、秒の端数) を入力するためのコントロールです。
      </td>
      <td class="code-example">
            <input type="datetime" value="2025-04-15 20:30:40" />
      </td>
    </tr>
  </tbody>
</table>