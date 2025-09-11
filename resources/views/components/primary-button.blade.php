<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-coffee inline-flex items-center justify-center font-semibold text-sm uppercase tracking-wider hover:scale-105 focus:outline-none focus:ring-2 focus:ring-coffee-300 focus:ring-offset-2 transition-all duration-300']) }}>
    {{ $slot }}
</button>
