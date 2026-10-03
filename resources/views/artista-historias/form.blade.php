<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select name="titulo_codigo" label="Título" :options="$titulos" :value="$artistaHistoria->titulo_codigo" required />
    <x-form.input type="number" name="exemplar_codigo" label="Exemplar (nº)" :value="$artistaHistoria->exemplar_codigo" min="1" required />
    <x-form.input type="number" name="historia_codigo" label="História (número)" :value="$artistaHistoria->historia_codigo" min="1" required />
    <x-form.select name="artista_codigo" label="Artista" :options="$artistas" :value="$artistaHistoria->artista_codigo" required />
    <x-form.input type="number" name="participa_artistahistoria" label="Participação" :value="$artistaHistoria->participa_artistahistoria" min="0" />
</div>
