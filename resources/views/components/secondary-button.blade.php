<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center font-semibold text-sm border-2 border-coffee-300 bg-cream-100 text-coffee-700 uppercase tracking-wider hover:bg-coffee-100 hover:border-coffee-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-coffee-300 focus:ring-offset-2 disabled:opacity-50 transition-all duration-300 px-4 py-2 rounded-xl shadow-sm']) }}>
    {{ $slot }}
</button>
