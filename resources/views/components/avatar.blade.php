@props(['name' => '', 'size' => 'md'])

@php
    $parts = preg_split('/\s+/', trim($name));
    $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
    if ($initials === '') $initials = '?';

    $colors = ['bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-lime-500', 'bg-emerald-500', 'bg-teal-500', 'bg-cyan-500', 'bg-blue-500', 'bg-indigo-500', 'bg-violet-500', 'bg-fuchsia-500', 'bg-pink-500'];
    $color = $colors[crc32($name) % count($colors)];

    $sizes = [
        'sm' => 'h-7 w-7 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-16 w-16 text-xl',
        'xl' => 'h-24 w-24 text-3xl',
    ][$size] ?? 'h-10 w-10 text-sm';
@endphp

<div class="rounded-full flex items-center justify-center text-white font-semibold {{ $color }} {{ $sizes }} shrink-0">
    {{ $initials }}
</div>
