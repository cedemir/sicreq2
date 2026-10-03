<x-layouts.app title="Histórias">
    <x-page-header eyebrow="Acervo" title="Histórias" description="Histórias publicadas em cada exemplar.">
        <x-slot:actions>
            <x-link-button :href="route('historias.create')">+ Nova história</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('historias.index')" placeholder="Pesquisar histórias..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Título</th>
                        <th class="px-3 py-3 text-left font-semibold">Exemplar (nº)</th>
                        <th class="px-3 py-3 text-left font-semibold">História (número)</th>
                        <th class="px-3 py-3 text-left font-semibold">Título da história</th>
                        <th class="px-3 py-3 text-left font-semibold">Colorida</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($historias as $historia)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4 text-slate-300">{{ $historia->titulo?->titulo_descricao }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $historia->exemplar_codigo }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $historia->historia_numero }}</td>
                            <td class="px-3 py-4 font-semibold text-white">{{ $historia->historia_titulo }}</td>
                            <td class="px-3 py-4 text-slate-300">
                                @if ($historia->historia_cor)
                                    <span class="rounded-full bg-blue-500/15 px-2 py-0.5 text-xs font-semibold text-blue-300">Sim</span>
                                @else
                                    <span class="text-slate-500">Não</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('historias.edit', $historia)"
                                    :destroy="route('historias.destroy', $historia)"
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

        @if ($historias->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $historias->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
