<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'back-button',
    'onclick' => 'history.back()' 
]) }}>
    {{ $slot }}
</button>
