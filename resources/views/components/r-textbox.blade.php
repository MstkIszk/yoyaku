
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
                {{-- {{ $slot }}には タグの開始(<x-rTextbox>)から終了(</x-rTextbox>) に指定された文字列などが入る --}}
                <label class="Guidelabel" for="{{ $name }}">{{ $slot }}</label>
            @endif
            {{-- {{ $attributes }}には xxx="" に指定されたもの以外のパラメータ(requidなど)が入る --}}
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="{{ $class }}" value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $attr }} {{ $attributes }}/>
        </div>
    </div>
