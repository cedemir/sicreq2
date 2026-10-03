<x-layouts.app title="Editar criador de personagem">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Personagens · Criadores de personagens" title="Editar criador de personagem" description="Altere os dados e salve para atualizar o registro." />

        <form method="POST" action="{{ route('personagem-criadores.update', $personagemCriador) }}">
            @csrf
            @method('PUT')

            <x-card class="p-6 sm:p-8">
                @include('personagem-criadores.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('personagem-criadores.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
