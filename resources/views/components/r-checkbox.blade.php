<div class="checkBoxframe">
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
    @if ($caption != '')
            <label class="Guidelabel" for="{{ $name }}">{{ $caption }}</label>
    @endif
    <div class="checkBoxArea">
        {{ $slot }}  {{-- 要素の内容で指定された定義を展開 --}}
    </div>
</div>
