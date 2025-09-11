@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-coffee-700']) }}>
    {{ $value ?? $slot }}
</label>
