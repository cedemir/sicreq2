<x-layouts.app title="Artistas x Histórias">
    <x-page-header eyebrow="Artistas" title="Artistas x Histórias" description="Participação dos artistas em cada história.">
        <x-slot:actions>
            <x-link-button :href="route('artista-historias.create')">+ Nova participação de artista</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('artista-historias.index')" placeholder="Pesquisar artistas x Histórias..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Título</th>
                        <th class="px-3 py-3 text-left font-semibold">Exemplar (nº)</th>
                        <th class="px-3 py-3 text-left font-semibold">História (número)</th>
                        <th class="px-3 py-3 text-left font-semibold">Artista</th>
                        <th class="px-3 py-3 text-left font-semibold">Participação</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($artistaHistorias as $artistaHistoria)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 font-semibold text-white">{{ $artistaHistoria->titulo?->titulo_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $artistaHistoria->exemplar_codigo }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $artistaHistoria->historia_codigo }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $artistaHistoria->artista?->artista_nome }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $artistaHistoria->participa_artistahistoria }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('artista-historias.edit', $artistaHistoria)"
                                    :destroy="route('artista-historias.destroy', $artistaHistoria)"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-12 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($artistaHistorias->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $artistaHistorias->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
