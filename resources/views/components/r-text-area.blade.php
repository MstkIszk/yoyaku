<div class="MemoArea">
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
    <label class="Guidelabel" for="{{ $name }}">{{ $slot }}</label>
    <textarea
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="{{ $class }}"
        placeholder="{{ $placeholder }}" 
    >{{ $msgText }}</textarea>  {{-- 要素の内容で指定された定義を展開 --}}
</div>
