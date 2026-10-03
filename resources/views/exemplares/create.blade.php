<x-layouts.app title="Novo exemplar">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Acervo · Exemplares" title="Novo exemplar" description="Preencha os dados para cadastrar um novo exemplar." />

        <form method="POST" action="{{ route('exemplares.store') }}" enctype="multipart/form-data">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('exemplares.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('exemplares.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
