<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'register-button']) }}>
    {{ $slot }}
</button>
