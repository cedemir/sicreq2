<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select name="personagem_codigo" label="Personagem" :options="$personagens" :value="$imagemPersonagem->personagem_codigo" required />
    <x-form.input type="number" name="imagem_codigo" label="Código da imagem" :value="$imagemPersonagem->imagem_codigo" min="1" />
    <x-form.input name="imagem_descricao" label="Descrição" :value="$imagemPersonagem->imagem_descricao" maxlength="150" />
</div>
