@props(['action', 'placeholder' => 'Pesquisar...'])

<form method="GET" action="{{ $action }}" class="mb-4 flex gap-3" role="search">
    <input
        type="search"
        name="busca"
        value="{{ request('busca') }}"
        placeholder="{{ $placeholder }}"
        aria-label="{{ $placeholder }}"
        class="w-full rounded-xl border border-navy-700 bg-navy-950 px-4 py-3 text-[15px] text-slate-100 placeholder:text-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
    >

    @if (request()->filled('busca'))
        <x-link-button :href="$action" variant="secondary">Limpar</x-link-button>
    @endif
</form>
