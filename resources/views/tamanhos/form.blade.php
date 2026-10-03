<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input name="tamanho_descricao" label="Descrição" :value="$tamanho->tamanho_descricao" maxlength="255" required />
    <x-form.input type="number" step="0.01" name="tamanho_linha" label="Linha" :value="$tamanho->tamanho_linha" min="0" />
    <x-form.input type="number" step="0.01" name="tamanho_coluna" label="Coluna" :value="$tamanho->tamanho_coluna" min="0" />
</div>
