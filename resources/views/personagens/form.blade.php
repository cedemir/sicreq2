<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input name="personagem_descricao" label="Nome" :value="$personagem->personagem_descricao" maxlength="50" required />
    <x-form.select name="universo_codigo" label="Universo" :options="$universos" :value="$personagem->universo_codigo" required />
    <x-form.textarea name="personagem_origem" label="Origem" :value="$personagem->personagem_origem" maxlength="16000" rows="8" />
</div>
