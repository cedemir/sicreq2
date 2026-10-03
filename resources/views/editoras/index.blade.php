<x-layouts.app title="Editoras">
    <x-page-header eyebrow="Tabelas auxiliares" title="Editoras" description="Editoras responsáveis pela publicação dos títulos.">
        <x-slot:actions>
            <x-link-button :href="route('editoras.create')">+ Nova editora</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('editoras.index')" placeholder="Pesquisar editoras..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Código</th>
                        <th class="px-3 py-3 text-left font-semibold">Nome</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($editoras as $editora)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $editora->editora_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $editora->editora_descricao }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('editoras.edit', $editora)"
                                    :destroy="route('editoras.destroy', $editora)"
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

        @if ($editoras->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $editoras->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
