<x-layouts.app title="Tipos de título">
    <x-page-header eyebrow="Tabelas auxiliares" title="Tipos de título" description="Classificação dos títulos por tipo de publicação.">
        <x-slot:actions>
            <x-link-button :href="route('tipos-titulo.create')">+ Novo tipo de título</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('tipos-titulo.index')" placeholder="Pesquisar tipos de título..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Código</th>
                        <th class="px-3 py-3 text-left font-semibold">Descrição</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($tiposTitulo as $tipoTitulo)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $tipoTitulo->tipotitulo_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $tipoTitulo->tipotitulo_descricao }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('tipos-titulo.edit', $tipoTitulo)"
                                    :destroy="route('tipos-titulo.destroy', $tipoTitulo)"
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

        @if ($tiposTitulo->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $tiposTitulo->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
