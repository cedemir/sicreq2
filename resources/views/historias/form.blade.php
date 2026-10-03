<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select name="titulo_codigo" label="Título" :options="$titulos" :value="$historia->titulo_codigo" required />
    <x-form.input type="number" name="exemplar_codigo" label="Exemplar (nº)" :value="$historia->exemplar_codigo" min="1" required />
    <x-form.input type="number" name="historia_numero" label="História (número)" :value="$historia->historia_numero" min="1" required />
    <x-form.input name="historia_titulo" label="Título da história" :value="$historia->historia_titulo" maxlength="50" required />
    <x-form.textarea name="historia_resumo" label="Resumo" :value="$historia->historia_resumo" maxlength="16000" rows="8" />
    <x-form.checkbox name="historia_cor" label="Colorida" :checked="(bool) $historia->historia_cor" />
    <x-form.input name="historia_original" label="Título original" :value="$historia->historia_original" maxlength="50" />
</div>
