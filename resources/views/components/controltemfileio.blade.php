<div>
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button type="button" id="saveButton" style="padding: 8px 16px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">書き込み</button>
        <input type="file" id="loadFileInput" style="display: none;">
        <button type="button" id="loadButton" style="padding: 8px 16px; background-color: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer;">読み込み</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveButton = document.getElementById('saveButton');
            const loadButton = document.getElementById('loadButton');
            const loadFileInput = document.getElementById('loadFileInput');
            const nameitem = "{{ $nameitem }}";
            const fileExtension = "{{ $extension }}";
            const form = document.querySelector('form');

            // 書き込みボタンのクリックイベント
            saveButton.addEventListener('click', function() {
                const form = document.querySelector('form');
                const data = {};
                const inputs = form.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    // '_token'はformが開かれたときに設定されるので保存項目から除外
                    // '_token'を上書きすると Laravelで ステータス 419 が発生する  
                    //if ((input.name) && (input.type != 'hidden')) {
                    //    data[input.name] = input.value;
                    //}
                    var name = input.name;

                    if (name && (input.type != 'hidden')) {

                        // チェックボックスの処理
                        if (input.type === 'checkbox') {
                            const isChecked = input.checked;
                            // 配列項目 (例: name="ResvTypeBit[]") の処理
                            if (name.endsWith('[]')) {
                                const baseName = name.slice(0, -2); // "ResvTypeBit" を取得
                                                        // 配列がない場合は初期化（ここではチェック状態とvalueをオブジェクトとして保存）
                                if (!data[baseName]) {
                                    data[baseName] = [];
                                }
                                                        // チェック状態と value をペアで保存
                                data[baseName].push({
                                    value: input.value,
                                    checked: isChecked
                                });
                            } 
                            // 通常のチェックボックス (例: name="IsEnabled") の処理
                            else {
                                data[name] = isChecked; // ON/OFFの状態（boolean）を保存
                            }
                        }
                        // 配列項目 (例: name="crName[]") の処理 (テキスト/数値)
                        else if (name.endsWith('[]')) {
                            const baseName = name.slice(0, -2); // "crName" を取得
                            // まだデータに配列がない場合は初期化
                            if (!data[baseName]) {
                                data[baseName] = [];
                            }
                            // 配列に値を追加
                            data[baseName].push(input.value);
                        }
                        // 通常の項目の処理
                        else if (input.type !== 'checkbox') {
                            data[name] = input.value;
                        }                    
                    }
                });
                const nameVal = data[nameitem];
                if (!nameVal) {
                    alert(nameVal + 'を入力してください。');
                    return;
                }1

                const fileName = nameVal +`${fileExtension}`;
                const json = JSON.stringify(data, null, 2);
                const blob = new Blob([json], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                alert('データがローカルに保存されました。');
            });

            // 読み込みボタンのクリックイベント
            loadButton.addEventListener('click', function() {
                loadFileInput.click();
            });

            // ファイルが選択されたときのイベント
            loadFileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const data = JSON.parse(e.target.result);
                        
                        // フォームへの値のセット
                        for (const name in data) {
                            const value = data[name];
                            
                            // 配列として保存された項目（チェックボックスや繰り返し項目など）の処理
                            if (Array.isArray(value)) {
                                // 配列項目（教室情報など）の処理（既存のテキスト/数値入力の処理）
                                if (typeof value[0] !== 'object' || value[0].checked === undefined) {
                                    // nameに[]を付加し、すべての該当するinput要素を取得
                                    const inputs = form.querySelectorAll(`[name="${name}[]"]`);

                                    value.forEach((val, index) => {
                                        // 配列の順番とinput要素の順番を一致させて値をセット
                                        if (inputs[index]) {
                                            inputs[index].value = val;
                                        }
                                    });
                                }
                                else {
                                    // 配列のチェックボックス (例: name="WaysPayBit[]") の処理
                                    const checkboxName = `${name}[]`;
                                    // フォーム内のすべての該当するチェックボックスのチェックを解除してから復元
                                    const allCheckboxes = form.querySelectorAll(`input[type="checkbox"][name="${checkboxName}"]`);
                                    allCheckboxes.forEach(cb => cb.checked = false);

                                    // 保存されたデータに基づいてチェック状態を復元
                                    value.forEach(item => {

                                        if (item.checked) {
                                            // valueとnameで要素を特定し、チェックを付ける
                                            const input = form.querySelector(`input[type="checkbox"][name="${checkboxName}"][value="${item.value}"]`);
                                            if (input) {
                                                input.checked = true;
                                            }
                                        }
                                    });

                                }
                            }
                            // 通常の項目の処理
                            else {
                                const input = form.querySelector(`[name="${name}"]`);
                                if (input) {
                                    if (input.type === 'checkbox') {
                                        // 通常のチェックボックス (例: name="IsEnabled") の復元
                                        input.checked = value;
                                    } else {
                                    // テキスト、数値、日付、時刻などの復元
                                        input.value = value;
                                    }
                                }
                            }
                        }
                        alert('データが読み込まれました。');
                    } catch (err) {
                        alert('ファイルの読み込みに失敗しました。');
                    }
                };
                reader.readAsText(file);
            });
        });
    </script>
</div>
