<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center font-semibold text-sm bg-red-600 text-white border-2 border-red-600 uppercase tracking-wider hover:bg-red-700 hover:border-red-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-all duration-300 px-4 py-2 rounded-xl shadow-sm']) }}>
    {{ $slot }}
</button>
