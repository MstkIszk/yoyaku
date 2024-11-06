<div>
    <x-input-error :messages="$errors->get('{{ $name }}')" class="mt-2" />
    <label for="{{ $name }}">{{ $slot }}</label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="{{ $class }}"
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}" 
        {{ $attributes }}
    />
</div>
