<x-layouts.app title="Exemplares">
    <x-page-header eyebrow="Acervo" title="Exemplares" description="Edições físicas de cada título, com capa, data, tamanho e local de armazenagem.">
        <x-slot:actions>
            <x-link-button :href="route('exemplares.relatorio')" target="_blank">Relatório PDF</x-link-button>
            <x-link-button :href="route('exemplares.lote.create')">Gerar em lote</x-link-button>
            <x-link-button :href="route('exemplares.create')">+ Novo exemplar</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('exemplares.index')" placeholder="Pesquisar exemplares..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Título</th>
                        <th class="px-3 py-3 text-left font-semibold">Número</th>
                        <th class="px-3 py-3 text-left font-semibold">Subtítulo</th>
                        <th class="px-3 py-3 text-left font-semibold">Capa</th>
                        <th class="px-3 py-3 text-left font-semibold">Data</th>
                        <th class="px-3 py-3 text-left font-semibold">Nº de páginas</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($exemplares as $exemplar)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-300">{{ $exemplar->titulo?->titulo_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $exemplar->exemplar_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $exemplar->exemplar_subtitulo }}</td>
                            <td class="px-3 py-4 text-slate-300">
                                @if ($exemplar->exemplar_capa)
                                    <img src="{{ $exemplar->imageUrl() }}" alt="" class="size-10 rounded-lg object-cover">
                                @endif
                            </td>
                            <td class="px-3 py-4 text-slate-300">{{ $exemplar->exemplar_data?->format('d/m/Y') }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $exemplar->exemplar_numeropagina }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('exemplares.edit', $exemplar)"
                                    :destroy="route('exemplares.destroy', $exemplar)"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($exemplares->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $exemplares->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
