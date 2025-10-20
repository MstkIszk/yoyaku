
    <div class="inputBoxframe">
        @php
            // $name (例: 'classrooms[1][crName]') をエラー取得用のキーに変換する
            // 'classrooms.1.crName' に変換される
            $errorKey = str_replace(['[', ']'], ['.', ''], $name);
            // 先頭と末尾の不要なドットを削除
            $errorKey = trim($errorKey, '.');
        @endphp
        <x-input-error :messages="$errors->get($errorKey)" class="mt-2" />
        <div class="inputBoxArea">
            @if ($slot != '')
                <label class="Guidelabel" for="{{ $name }}">{{ $slot }}</label>
            @endif
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="{{ $class }}" value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $attributes }} />
        </div>
    </div>
