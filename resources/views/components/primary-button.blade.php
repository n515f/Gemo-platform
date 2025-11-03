<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn--add']) }}>
    {{ $slot }}
</button>
