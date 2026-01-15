{{-- resources/views/components/nav-link.blade.php --}}

@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'border-emerald-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition'
        : 'border-transparent text-gray-500 hover:text-emerald-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>