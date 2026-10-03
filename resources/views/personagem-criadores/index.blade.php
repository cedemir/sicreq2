<x-layouts.app title="Criadores de personagens">
    <x-page-header eyebrow="Personagens" title="Criadores de personagens" description="Artistas responsáveis pela criação de cada personagem.">
        <x-slot:actions>
            <x-link-button :href="route('personagem-criadores.create')">+ Novo criador de personagem</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('personagem-criadores.index')" placeholder="Pesquisar criadores de personagens..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Personagem</th>
                        <th class="px-3 py-3 text-left font-semibold">Artista</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($personagemCriadores as $personagemCriador)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 font-semibold text-white">{{ $personagemCriador->personagem?->personagem_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $personagemCriador->artista?->artista_nome }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('personagem-criadores.edit', $personagemCriador)"
                                    :destroy="route('personagem-criadores.destroy', $personagemCriador)"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-12 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($personagemCriadores->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $personagemCriadores->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
