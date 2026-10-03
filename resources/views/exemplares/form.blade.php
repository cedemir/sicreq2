<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select name="titulo_codigo" label="Título" :options="$titulos" :value="$exemplar->titulo_codigo" required />
    <x-form.input type="number" name="exemplar_codigo" label="Número" :value="$exemplar->exemplar_codigo" min="1" required />
    <x-form.input name="exemplar_subtitulo" label="Subtítulo" :value="$exemplar->exemplar_subtitulo" maxlength="255" />
    <x-form.image name="exemplar_capa" label="Capa" :url="$exemplar->imageUrl()" />
    <x-form.input type="date" name="exemplar_data" label="Data" :value="$exemplar->exemplar_data?->format('Y-m-d')" />
    <x-form.select name="armazenagem_codigo" label="Armazenagem" :options="$armazenagens" :value="$exemplar->armazenagem_codigo" />
    <x-form.select name="tamanho_codigo" label="Tamanho" :options="$tamanhos" :value="$exemplar->tamanho_codigo" />
    <x-form.input type="number" name="exemplar_situacao" label="Situação" :value="$exemplar->exemplar_situacao" min="0" />
    <x-form.input type="number" name="exemplar_numeropagina" label="Nº de páginas" :value="$exemplar->exemplar_numeropagina" min="0" />
</div>
