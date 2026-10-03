<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input name="artista_nome" label="Nome" :value="$artista->artista_nome" maxlength="50" required />
    <x-form.textarea name="artista_historico" label="Histórico" :value="$artista->artista_historico" maxlength="255" />
    <x-form.image name="artista_foto" label="Foto" :url="$artista->imageUrl()" />
</div>
