<div class="inputBoxframe">
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
    <div class="inputBoxArea">
        @if ($caption != '')
            <label class="Guidelabel" for="{{ $name }}">{{ $caption }}</label>
        @endif
        <select name="{{ $name }}" id="{{ $name }}" class="{{ $class }}"  {{ $attributes }} >
            <option value="">選択してください</option>
            {{ $slot }}  {{-- 要素の内容で指定された定義を展開 --}}
        </select>
    </div>
</div>
