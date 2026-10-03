<x-layouts.app title="Histórias x Personagens">
    <x-page-header eyebrow="Personagens" title="Histórias x Personagens" description="Personagens que aparecem em cada história.">
        <x-slot:actions>
            <x-link-button :href="route('historia-personagens.create')">+ Novo personagem em história</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('historia-personagens.index')" placeholder="Pesquisar histórias x Personagens..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Título</th>
                        <th class="px-3 py-3 text-left font-semibold">Exemplar (nº)</th>
                        <th class="px-3 py-3 text-left font-semibold">História (número)</th>
                        <th class="px-3 py-3 text-left font-semibold">Personagem</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($historiaPersonagens as $historiaPersonagem)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 font-semibold text-white">{{ $historiaPersonagem->titulo?->titulo_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $historiaPersonagem->exemplar_codigo }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $historiaPersonagem->historia_codigo }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $historiaPersonagem->personagem?->personagem_descricao }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('historia-personagens.edit', $historiaPersonagem)"
                                    :destroy="route('historia-personagens.destroy', $historiaPersonagem)"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-12 text-center text-slate-500">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($historiaPersonagens->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $historiaPersonagens->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
