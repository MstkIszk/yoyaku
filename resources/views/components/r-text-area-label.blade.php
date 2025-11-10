<div class="MemoArea">
    <label class="Guidelabel" for="{{ $name }}">{{ $slot }}</label>
    <textarea
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="InputBox_Label txtWidth_96pc {{ $class }}"
        rows="{{ $rows }}"
        readonly
    >{{ $msgText }}</textarea>
</div>
