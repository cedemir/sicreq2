<x-layouts.app title="Artistas">
    <x-page-header eyebrow="Artistas" title="Artistas" description="Roteiristas, desenhistas e demais artistas do acervo.">
        <x-slot:actions>
            <x-link-button :href="route('artistas.create')">+ Novo artista</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('artistas.index')" placeholder="Pesquisar artistas..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Código</th>
                        <th class="px-3 py-3 text-left font-semibold">Nome</th>
                        <th class="px-3 py-3 text-left font-semibold">Foto</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($artistas as $artista)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $artista->artista_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $artista->artista_nome }}</td>
                            <td class="px-3 py-4 text-slate-300">
                                @if ($artista->artista_foto)
                                    <img src="{{ $artista->imageUrl() }}" alt="" class="size-10 rounded-lg object-cover">
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('artistas.edit', $artista)"
                                    :destroy="route('artistas.destroy', $artista)"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-12 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($artistas->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $artistas->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
