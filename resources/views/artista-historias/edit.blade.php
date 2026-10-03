<x-layouts.app title="Editar participação de artista">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Artistas · Artistas x Histórias" title="Editar participação de artista" description="Altere os dados e salve para atualizar o registro." />

        <form method="POST" action="{{ route('artista-historias.update', $artistaHistoria) }}">
            @csrf
            @method('PUT')

            <x-card class="p-6 sm:p-8">
                @include('artista-historias.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('artista-historias.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
