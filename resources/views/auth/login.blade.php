<x-layouts.guest title="Entrar">
    <x-card class="p-6 sm:p-8">
        <h1 class="text-xl font-bold text-white">Acesse sua conta</h1>
        <p class="mt-1 text-sm text-slate-400">Informe seu e-mail e senha para continuar.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-6 flex flex-col gap-5">
            @csrf

            <x-form.input type="email" name="email" label="E-mail" autocomplete="username" autofocus required />
            <x-form.input type="password" name="password" label="Senha" autocomplete="current-password" required />

            <label for="remember" class="inline-flex items-center gap-3 text-sm text-slate-300">
                <input type="checkbox" id="remember" name="remember" value="1" class="size-4 rounded accent-blue-600" @checked(old('remember'))>
                Manter conectado
            </label>

            <x-button class="w-full">Entrar</x-button>
        </form>
    </x-card>
</x-layouts.guest>
