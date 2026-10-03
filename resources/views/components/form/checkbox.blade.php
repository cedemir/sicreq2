@props(['name', 'label', 'checked' => false])

<div class="flex flex-col gap-1.5">
    <label for="{{ $name }}" class="inline-flex items-center gap-3 pt-7 text-sm font-medium text-slate-200">
        <input
            type="checkbox"
            id="{{ $name }}"
            name="{{ $name }}"
            value="1"
            @checked(old($name, $checked))
            {{ $attributes->class('size-4 rounded accent-blue-600') }}
        >
        {{ $label }}
    </label>

    @error($name)
        <p class="text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>
