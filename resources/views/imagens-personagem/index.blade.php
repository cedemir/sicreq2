<x-layouts.app title="Imagens de personagens">
    <x-page-header eyebrow="Personagens" title="Imagens de personagens" description="Imagens associadas a cada personagem.">
        <x-slot:actions>
            <x-link-button :href="route('imagens-personagem.create')">+ Nova imagem de personagem</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('imagens-personagem.index')" placeholder="Pesquisar imagens de personagens..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Personagem</th>
                        <th class="px-3 py-3 text-left font-semibold">Código da imagem</th>
                        <th class="px-3 py-3 text-left font-semibold">Descrição</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($imagensPersonagem as $imagemPersonagem)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-300">{{ $imagemPersonagem->personagem?->personagem_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $imagemPersonagem->imagem_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $imagemPersonagem->imagem_descricao }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('imagens-personagem.edit', $imagemPersonagem)"
                                    :destroy="route('imagens-personagem.destroy', $imagemPersonagem)"
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

        @if ($imagensPersonagem->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $imagensPersonagem->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
