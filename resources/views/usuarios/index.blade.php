<x-layouts.app title="Usuários">
    <x-page-header eyebrow="Administração" title="Usuários" description="Pessoas com acesso ao sistema.">
        <x-slot:actions>
            <x-link-button :href="route('usuarios.create')">+ Novo usuário</x-link-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <x-search-form :action="route('usuarios.index')" placeholder="Pesquisar usuários..." />

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-700 text-[11px] tracking-wider text-slate-400 uppercase">
                        <th class="px-3 py-3 text-left font-semibold">Nome</th>
                        <th class="px-3 py-3 text-left font-semibold">E-mail</th>
                        <th class="px-3 py-3 text-left font-semibold">Cadastrado em</th>
                        <th class="px-3 py-3 text-left font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-700">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-navy-800/50">
                            <td class="px-3 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">{{ $user->initials() }}</span>
                                    <span class="font-semibold text-white">{{ $user->name }}</span>
                                    @if (auth()->user()->is($user))
                                        <span class="rounded-full bg-blue-500/15 px-2 py-0.5 text-xs font-semibold text-blue-300">você</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-4 text-slate-300">{{ $user->email }}</td>
                            <td class="px-3 py-4 text-slate-300 tabular-nums">{{ $user->created_at?->format('d/m/Y') }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <x-row-actions
                                    :edit="route('usuarios.edit', $user)"
                                    :destroy="route('usuarios.destroy', $user)"
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

        @if ($users->hasPages())
            <div class="mt-5 border-t border-navy-700 pt-5">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>
</x-layouts.app>
