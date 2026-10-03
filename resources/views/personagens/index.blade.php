<x-layouts.app title="Personagens">
    <x-page-header eyebrow="Personagens" title="Personagens" description="Personagens do acervo e o universo ao qual pertencem.">
        <x-slot:actions>
            <x-link-button :href="route('personagens.create')">+ Novo personagem</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('personagens.index')" placeholder="Pesquisar personagens..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Código</th>
                        <th class="px-3 py-3 text-left font-semibold">Nome</th>
                        <th class="px-3 py-3 text-left font-semibold">Universo</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($personagens as $personagem)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-400 tabular-nums">{{ $personagem->personagem_codigo }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $personagem->personagem_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300">{{ $personagem->universo?->universo_descricao }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('personagens.edit', $personagem)"
                                    :destroy="route('personagens.destroy', $personagem)"
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

        @if ($personagens->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $personagens->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
