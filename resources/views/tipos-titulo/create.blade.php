<x-layouts.app title="Novo tipo de título">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Tabelas auxiliares · Tipos de título" title="Novo tipo de título" description="Preencha os dados para cadastrar um novo tipo de título." />

        <form method="POST" action="{{ route('tipos-titulo.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('tipos-titulo.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('tipos-titulo.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
