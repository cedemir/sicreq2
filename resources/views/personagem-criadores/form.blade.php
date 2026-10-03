<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select name="personagem_codigo" label="Personagem" :options="$personagens" :value="$personagemCriador->personagem_codigo" required />
    <x-form.select name="artista_codigo" label="Artista" :options="$artistas" :value="$personagemCriador->artista_codigo" required />
</div>
