@props(['name', 'label', 'required' => false])

<div {{ $attributes->class('flex flex-col gap-1.5') }}>
    <label for="{{ $name }}" class="text-xs font-semibold tracking-wider text-slate-400 uppercase">
        {{ $label }}
        @if ($required)
            <span class="text-blue-400" aria-hidden="true">*</span>
        @endif
    </label>

    {{ $slot }}

    @error($name)
        <p class="text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>
