<div class="checkBoxframe">
    @php
        // $name (例: 'classrooms[1][crName]') をエラー取得用のキーに変換する
        // 'classrooms.1.crName' に変換される
        $errorKey = str_replace(['[', ']'], ['.', ''], $name);
        // 先頭と末尾の不要なドットを削除
        $errorKey = trim($errorKey, '.');
    @endphp
    <x-input-error :messages="$errors->get($errorKey)" class="mt-2" />
    {{--<x-input-error :messages="$errors->get($name)" class="mt-2" />--}}
    @if ($caption != '')
            <label class="Guidelabel" for="{{ $name }}">{{ $caption }}</label>
    @endif
    <div class="checkBox-LineArea">
        {{ $slot }}  {{-- 要素の内容で指定された定義を展開 --}}
    </div>
</div>
