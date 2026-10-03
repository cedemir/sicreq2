<x-layouts.app title="Novo usuário">
    <div class="mx-auto max-w-4xl">
        <x-page-header eyebrow="Administração · Usuários" title="Novo usuário" description="Preencha os dados para cadastrar um novo usuário." />

        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf

            <x-card class="p-6 sm:p-8">
                @include('usuarios.form')

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-navy-700 pt-6">
                    <x-link-button :href="route('usuarios.index')" variant="secondary">Cancelar</x-link-button>
                    <x-button>Salvar</x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-layouts.app>
