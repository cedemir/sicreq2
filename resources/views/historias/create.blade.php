<x-layouts.app title="Nova história">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Acervo · Histórias" title="Nova história" description="Preencha os dados para cadastrar uma nova história." />

        <form method="POST" action="{{ route('historias.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('historias.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('historias.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
