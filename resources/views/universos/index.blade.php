<x-layouts.app title="Universos">
    <x-page-header eyebrow="Tabelas auxiliares" title="Universos" description="Universos ficcionais aos quais os personagens pertencem.">
        <x-slot:actions>
            <x-link-button :href="route('universos.create')">+ Novo universo</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('universos.index')" placeholder="Pesquisar universos..." />

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
                    @forelse ($universos as $universo)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $universo->universo_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $universo->universo_descricao }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('universos.edit', $universo)"
                                    :destroy="route('universos.destroy', $universo)"
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

        @if ($universos->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $universos->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
