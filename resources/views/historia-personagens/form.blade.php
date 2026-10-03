<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select name="titulo_codigo" label="Título" :options="$titulos" :value="$historiaPersonagem->titulo_codigo" required />
    <x-form.input type="number" name="exemplar_codigo" label="Exemplar (nº)" :value="$historiaPersonagem->exemplar_codigo" min="1" required />
    <x-form.input type="number" name="historia_codigo" label="História (número)" :value="$historiaPersonagem->historia_codigo" min="1" required />
    <x-form.select name="personagem_codigo" label="Personagem" :options="$personagens" :value="$historiaPersonagem->personagem_codigo" required />
</div>
