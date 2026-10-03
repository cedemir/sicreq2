<x-layouts.app title="Novo personagem em história">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Personagens · Histórias x Personagens" title="Novo personagem em história" description="Preencha os dados para cadastrar um novo personagem em história." />

        <form method="POST" action="{{ route('historia-personagens.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('historia-personagens.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('historia-personagens.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
