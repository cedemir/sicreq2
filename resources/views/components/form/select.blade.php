@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'placeholder' => 'Selecione...'])

@php($selected = (string) old($name, $value))

<x-form.field :name="$name" :label="$label" :required="$required">
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @required($required)
        {{ $attributes->class([
            'rounded-xl border bg-navy-950 px-4 py-2.5 text-[15px] text-slate-100 placeholder:text-slate-500 focus:ring-1 focus:outline-none',
            'border-navy-700 focus:border-blue-500 focus:ring-blue-500' => ! $errors->has($name),
            'border-red-500/70 focus:border-red-500 focus:ring-red-500' => $errors->has($name),
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</x-form.field>
