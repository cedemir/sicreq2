<x-layouts.app title="Novo universo">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Tabelas auxiliares · Universos" title="Novo universo" description="Preencha os dados para cadastrar um novo universo." />

        <form method="POST" action="{{ route('universos.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('universos.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('universos.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
