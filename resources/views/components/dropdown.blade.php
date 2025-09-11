@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-2 glass bg-cream-50/95 border border-coffee-200/20'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 transform -translate-y-2"
            class="absolute z-50 mt-3 {{ $width }} rounded-xl shadow-2xl {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-xl ring-1 ring-coffee-200/30 shadow-lg {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
