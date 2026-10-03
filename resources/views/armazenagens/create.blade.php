<x-layouts.app title="Nova armazenagem">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Tabelas auxiliares · Armazenagens" title="Nova armazenagem" description="Preencha os dados para cadastrar uma nova armazenagem." />

        <form method="POST" action="{{ route('armazenagens.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('armazenagens.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('armazenagens.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
