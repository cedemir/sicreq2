@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false])

<x-form.field :name="$name" :label="$label" :required="$required">
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        @required($required)
        {{ $attributes->class([
            'rounded-xl border bg-navy-950 px-4 py-2.5 text-[15px] text-slate-100 placeholder:text-slate-500 focus:ring-1 focus:outline-none',
            'border-navy-700 focus:border-blue-500 focus:ring-blue-500' => ! $errors->has($name),
            'border-red-500/70 focus:border-red-500 focus:ring-red-500' => $errors->has($name),
        ]) }}
    >
</x-form.field>
