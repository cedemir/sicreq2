<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input name="name" label="Nome" :value="$user->name" maxlength="255" required />
    <x-form.input type="email" name="email" label="E-mail" :value="$user->email" maxlength="255" autocomplete="off" required />
    <x-form.input type="password" name="password" label="Senha" autocomplete="new-password" :required="! $user->exists" />
    <x-form.input type="password" name="password_confirmation" label="Confirmar senha" autocomplete="new-password" :required="! $user->exists" />

    @if ($user->exists)
        <p class="text-sm text-slate-400 sm:col-span-2">Deixe a senha em branco para manter a atual.</p>
    @endif
</div>
