<x-layouts.app title="Editar universo">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Tabelas auxiliares · Universos" title="Editar universo" description="Altere os dados e salve para atualizar o registro." />

        <form method="POST" action="{{ route('universos.update', $universo) }}">
            @csrf
            @method('PUT')

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
