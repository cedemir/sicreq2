<x-layouts.app title="Editar editora">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Tabelas auxiliares · Editoras" title="Editar editora" description="Altere os dados e salve para atualizar o registro." />

        <form method="POST" action="{{ route('editoras.update', $editora) }}">
            @csrf
            @method('PUT')

            <x-card class="p-6 sm:p-8">
                @include('editoras.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('editoras.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
