<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input name="titulo_descricao" label="Nome" :value="$titulo->titulo_descricao" maxlength="50" required />
    <x-form.select name="editora_codigo" label="Editora" :options="$editoras" :value="$titulo->editora_codigo" required />
    <x-form.select name="tipotitulo_codigo" label="Tipo" :options="$tiposTitulo" :value="$titulo->tipotitulo_codigo" required />
    <x-form.select name="situacao_codigo" label="Situação" :options="$situacoesTitulo" :value="$titulo->situacao_codigo" required />
    <x-form.input type="number" name="titulo_periodicidade" label="Periodicidade" :value="$titulo->titulo_periodicidade" min="0" />
    <x-form.input name="titulo_origem" label="Origem" :value="$titulo->titulo_origem" maxlength="50" />
</div>
