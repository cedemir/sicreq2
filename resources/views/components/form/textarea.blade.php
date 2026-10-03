@props(['name', 'label', 'value' => null, 'required' => false, 'rows' => 4])

<x-form.field :name="$name" :label="$label" :required="$required" class="sm:col-span-2">
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @required($required)
        {{ $attributes->class([
            'rounded-xl border bg-navy-950 px-4 py-2.5 text-[15px] text-slate-100 placeholder:text-slate-500 focus:ring-1 focus:outline-none',
            'border-navy-700 focus:border-blue-500 focus:ring-blue-500' => ! $errors->has($name),
            'border-red-500/70 focus:border-red-500 focus:ring-red-500' => $errors->has($name),
        ]) }}
    >{{ old($name, $value) }}</textarea>
</x-form.field>
