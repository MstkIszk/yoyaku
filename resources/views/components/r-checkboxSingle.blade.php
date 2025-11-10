<div class="checkbox-line-button">
    @if ($slot != '')
        <label class="Guidelabel" for="{{ $name }}">{{ $slot }}</label>
    @endif
    
    <div class="checkbox-button-container">
        {{-- 既存のinput要素を配置 --}}
        <input 
            type="checkbox" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="{{ $class }}" 
            {{-- readonlyはチェックボックスに適用しても意味がないことが多いのでここでは削除 --}}
            {{ $attributes }} 
            value="1" {{-- ON/OFF状態を表すため、valueは固定値(1など)を想定 --}}
            @checked(old($name, $value))
        >
        {{-- トグルボタンとして機能するラベル (inputの後に配置) --}}
        <label class="toggle-label" for="{{ $name }}"></label>
    </div>
</div>
