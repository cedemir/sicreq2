<x-layouts.app title="Tamanhos">
    <x-page-header eyebrow="Tabelas auxiliares" title="Tamanhos" description="Formatos físicos dos exemplares.">
        <x-slot:actions>
            <x-link-button :href="route('tamanhos.create')">+ Novo tamanho</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('tamanhos.index')" placeholder="Pesquisar tamanhos..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Código</th>
                        <th class="px-3 py-3 text-left font-semibold">Descrição</th>
                        <th class="px-3 py-3 text-left font-semibold">Linha</th>
                        <th class="px-3 py-3 text-left font-semibold">Coluna</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($tamanhos as $tamanho)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $tamanho->tamanho_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $tamanho->tamanho_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $tamanho->tamanho_linha }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $tamanho->tamanho_coluna }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('tamanhos.edit', $tamanho)"
                                    :destroy="route('tamanhos.destroy', $tamanho)"
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

        @if ($tamanhos->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $tamanhos->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
