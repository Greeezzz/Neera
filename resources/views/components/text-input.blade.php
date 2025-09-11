@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-cream-200 bg-cream-50 text-coffee-700 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 rounded-xl shadow-sm transition-all duration-300 placeholder-coffee-400']) }}>
