<x-layouts.app title="Gerar exemplares em lote">
    <div class="mx-auto max-w-4xl">
        <x-page-header
            eyebrow="Acervo · Exemplares"
            title="Gerar exemplares em lote"
            description="Cadastra de uma só vez todos os exemplares de um título, do número inicial ao final. Números que já existem são mantidos sem alteração."
        />

        <form method="POST" action="{{ route('exemplares.lote.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-form.select name="titulo_codigo" label="Título" :options="$titulos" required />
                    </div>

                    <x-form.input type="number" name="exemplar_inicial" label="Exemplar inicial" min="1" required />
                    <x-form.input type="number" name="exemplar_final" label="Exemplar final" min="1" required />

                    <x-form.select name="armazenagem_codigo" label="Armazenagem" :options="$armazenagens" />
                    <x-form.select name="tamanho_codigo" label="Tamanho" :options="$tamanhos" />

                    <x-form.input type="number" name="exemplar_numeropagina" label="Nº de páginas" min="0" />
                </div>

                <p class="mt-6 text-sm text-slate-400">
                    Armazenagem, tamanho e nº de páginas são aplicados a todos os exemplares gerados. É possível gerar até {{ number_format($maxExemplares, 0, ',', '.') }} exemplares por vez.
                </p>

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('exemplares.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Gerar exemplares</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
