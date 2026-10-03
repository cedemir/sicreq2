@props(['variant' => 'primary', 'type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->class([
    'inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg px-5 py-2.5 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 focus-visible:ring-offset-navy-950',
    'bg-blue-600 text-white shadow-lg shadow-blue-600/30 hover:bg-blue-500' => $variant === 'primary',
    'border border-navy-700 bg-navy-900 text-slate-200 hover:border-navy-600 hover:bg-navy-800' => $variant === 'secondary',
]) }}>
    {{ $slot }}
</button>
