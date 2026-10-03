@props(['name', 'label', 'url' => null])

<x-form.field :name="$name" :label="$label" class="sm:col-span-2">
    <div class="flex items-center gap-4 rounded-xl border border-dashed border-navy-700 bg-navy-950 p-4">
        @if ($url)
            <img src="{{ $url }}" alt="{{ $label }} atual" class="size-20 rounded-lg object-cover">
        @endif

        <input
            type="file"
            id="{{ $name }}"
            name="{{ $name }}"
            accept="image/*"
            {{ $attributes->class('text-sm text-slate-300 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-500') }}
        >
    </div>
</x-form.field>
