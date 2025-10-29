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
                        // 配列項目 (例: name="crName[]") の処理
                        if (name.endsWith('[]')) {
                            const baseName = name.slice(0, -2); // "crName" を取得
                            
                            // まだデータに配列がない場合は初期化
                            if (!data[baseName]) {
                                data[baseName] = [];
                            }
                            
                            // 配列に値を追加
                            data[baseName].push(input.value);
                        } 
                        // 通常の項目の処理
                        else {
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
                            
                            // 配列として保存された項目（教室情報など）の処理
                            if (Array.isArray(value)) {
                                // nameに[]を付加し、すべての該当するinput要素を取得
                                const inputs = form.querySelectorAll(`[name="${name}[]"]`);
                                
                                value.forEach((val, index) => {
                                    // 配列の順番とinput要素の順番を一致させて値をセット
                                    if (inputs[index]) {
                                        inputs[index].value = val;
                                    }
                                });
                            } 
                            // 通常の項目の処理
                            else {
                                const input = form.querySelector(`[name="${name}"]`);
                                if (input) {
                                    input.value = value;
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
