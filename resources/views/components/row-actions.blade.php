@props(['edit', 'destroy'])

<div class="inline-flex items-center gap-2">
    <a href="{{ $edit }}" class="rounded-md border border-navy-700 bg-navy-950 px-2.5 py-1.5 text-xs font-medium text-slate-100 transition hover:border-navy-600 hover:bg-navy-800">Editar</a>

    <form method="POST" action="{{ $destroy }}" onsubmit="return confirm('Confirma a exclusão deste registro?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="cursor-pointer rounded-md border border-navy-700 bg-navy-950 px-2.5 py-1.5 text-xs font-medium text-slate-100 transition hover:border-red-500/60 hover:bg-red-500/10 hover:text-red-300">Excluir</button>
    </form>
</div>
