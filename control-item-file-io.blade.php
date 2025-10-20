@props(['extension' => '.shop'])

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
            const fileExtension = "{{ $extension }}";

            // 書き込みボタンのクリックイベント
            saveButton.addEventListener('click', function() {
                const form = document.querySelector('form');
                const data = {};
                const inputs = form.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    if (input.name) {
                        data[input.name] = input.value;
                    }
                });

                const userID = data['UserID'];
                if (!userID) {
                    alert('UserIDを入力してください。');
                    return;
                }

                const fileName = `${userID}${fileExtension}`;
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
                        const form = document.querySelector('form');
                        for (const name in data) {
                            const input = form.querySelector(`[name="${name}"]`);
                            if (input) {
                                input.value = data[name];
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
