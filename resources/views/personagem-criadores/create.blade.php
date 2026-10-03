<x-layouts.app title="Novo criador de personagem">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Personagens · Criadores de personagens" title="Novo criador de personagem" description="Preencha os dados para cadastrar um novo criador de personagem." />

        <form method="POST" action="{{ route('personagem-criadores.store') }}">
            @csrf

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
