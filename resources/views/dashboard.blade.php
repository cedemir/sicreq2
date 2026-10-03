<x-layouts.app title="Painel">
    <x-page-header eyebrow="Visão geral" title="Painel" description="Quantidade de registros cadastrados em cada tabela do acervo." />

    <div class="space-y-10">
        @foreach (config('menu') as $group => $items)
            @php($items = array_intersect_key($items, $counts))
            @continue($items === [])

            <section>
                <h2 class="mb-4 text-xs font-bold tracking-[0.2em] text-slate-400 uppercase">{{ $group }}</h2>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($items as $route => $item)
                        <a href="{{ route("{$route}.index") }}" class="group rounded-2xl border border-navy-700 bg-navy-850 p-5 shadow-xl shadow-black/20 transition hover:border-blue-500/60">
                            <p class="flex items-center gap-2 text-sm font-medium text-slate-400 group-hover:text-slate-200">
                                <span class="text-xs" aria-hidden="true">{{ $item['icon'] }}</span>
                                {{ $item['label'] }}
                            </p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($counts[$route], 0, ',', '.') }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-layouts.app>
