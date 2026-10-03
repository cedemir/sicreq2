<x-layouts.app title="Novo personagem">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Personagens · Personagens" title="Novo personagem" description="Preencha os dados para cadastrar um novo personagem." />

        <form method="POST" action="{{ route('personagens.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('personagens.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('personagens.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
