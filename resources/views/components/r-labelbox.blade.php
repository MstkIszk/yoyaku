
    <div class="inputBoxArea">
        @if ($slot != '')
            <label class="Guidelabel" for="{{ $name }}">{{ $slot }}</label>
        @endif
        <input type="text" name="{{ $name }}" id="{{ $name }}" class="{{ $class }}" readonly {{ $attributes }} value="{{ $value }}">
    </div>
