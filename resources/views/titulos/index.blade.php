<x-layouts.app title="Títulos">
    <x-page-header eyebrow="Acervo" title="Títulos" description="Séries e publicações do acervo, com editora, tipo e situação.">
        <x-slot:actions>
            <x-link-button :href="route('titulos.create')">+ Novo título</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('titulos.index')" placeholder="Pesquisar títulos..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Código</th>
                        <th class="px-3 py-3 text-left font-semibold">Nome</th>
                        <th class="px-3 py-3 text-left font-semibold">Editora</th>
                        <th class="px-3 py-3 text-left font-semibold">Tipo</th>
                        <th class="px-3 py-3 text-left font-semibold">Situação</th>
                        <th class="px-3 py-3 text-left font-semibold">Periodicidade</th>
                        <th class="px-3 py-3 text-left font-semibold">Origem</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($titulos as $titulo)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $titulo->titulo_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $titulo->titulo_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $titulo->editora?->editora_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $titulo->tipoTitulo?->tipotitulo_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $titulo->situacao?->situacao_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $titulo->titulo_periodicidade }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $titulo->titulo_origem }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('titulos.edit', $titulo)"
                                    :destroy="route('titulos.destroy', $titulo)"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-12 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($titulos->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $titulos->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
