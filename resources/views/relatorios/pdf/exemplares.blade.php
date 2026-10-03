@php
    $totalExemplares = $titulos->sum(fn ($titulo) => $titulo->exemplares->count());
    $totalPaginas = $titulos->sum(fn ($titulo) => $titulo->exemplares->sum('exemplar_numeropagina'));
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <title>Relatório de exemplares</title>
        <style>
            @page { margin: 36px 36px 48px; }
            * { font-family: 'DejaVu Sans', sans-serif; }
            body { margin: 0; color: #0f172a; font-size: 9px; }
            .header { background: #0b1324; color: #ffffff; padding: 16px 18px; border-radius: 8px; }
            .header table { width: 100%; border-collapse: collapse; }
            .logo { width: 30px; height: 21px; padding-top: 9px; background: #2563eb; color: #ffffff; border-radius: 6px; text-align: center; font-weight: bold; font-size: 11px; line-height: 1; }
            .brand { font-size: 13px; font-weight: bold; padding-left: 10px; }
            .brand small { display: block; font-size: 8px; font-weight: normal; color: #94a3b8; }
            .meta { text-align: right; font-size: 8px; color: #cbd5e1; line-height: 1.5; }
            .eyebrow { margin: 20px 0 2px; font-size: 8px; font-weight: bold; letter-spacing: 2px; color: #2563eb; text-transform: uppercase; }
            h1 { margin: 0 0 12px; font-size: 18px; }
            .totals { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
            .totals td { width: 33%; padding: 10px 12px; background: #eff6ff; border: 2px solid #ffffff; }
            .totals span { display: block; font-size: 7px; font-weight: bold; letter-spacing: 1px; color: #1d4ed8; text-transform: uppercase; }
            .totals strong { font-size: 14px; color: #1e3a8a; }
            table.list { width: 100%; border-collapse: collapse; }
            table.list th { background: #1e3a8a; color: #ffffff; font-size: 7.5px; letter-spacing: 1px; text-transform: uppercase; text-align: left; padding: 7px 8px; }
            table.list td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; }
            table.list tr.group td { padding: 9px 8px 6px; background: #f1f5f9; border-bottom: 2px solid #cbd5e1; }
            table.list tr.group strong { font-size: 10px; }
            table.list tr.group span { color: #64748b; }
            table.list tr.group .count { float: right; font-weight: bold; color: #1d4ed8; }
            .number { text-align: right; }
            .muted { color: #94a3b8; }
            .empty { padding: 24px; text-align: center; color: #64748b; }
        </style>
    </head>
    <body>
        <div class="header">
            <table>
                <tr>
                    <td style="width: 30px;"><div class="logo">{{ Str::upper(Str::substr(config('app.name'), 0, 2)) }}</div></td>
                    <td class="brand">{{ config('app.name') }}<small>Catálogo de quadrinhos</small></td>
                    <td class="meta">
                        Gerado em {{ $geradoEm->format('d/m/Y \à\s H:i') }}<br>
                        por {{ $geradoPor }}
                    </td>
                </tr>
            </table>
        </div>

        <p class="eyebrow">Relatório de exemplares</p>
        <h1>Exemplares do acervo</h1>

        <table class="totals">
            <tr>
                <td><span>Títulos</span><strong>{{ number_format($titulos->count(), 0, ',', '.') }}</strong></td>
                <td><span>Exemplares</span><strong>{{ number_format($totalExemplares, 0, ',', '.') }}</strong></td>
                <td><span>Páginas</span><strong>{{ number_format($totalPaginas, 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        <table class="list">
            <thead>
                <tr>
                    <th class="number" style="width: 7%;">Nº</th>
                    <th style="width: 31%;">Subtítulo</th>
                    <th style="width: 11%;">Data</th>
                    <th style="width: 18%;">Armazenagem</th>
                    <th style="width: 15%;">Tamanho</th>
                    <th class="number" style="width: 8%;">Situação</th>
                    <th class="number" style="width: 10%;">Páginas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($titulos as $titulo)
                    <tr class="group">
                        <td colspan="7">
                            <span class="count">{{ $titulo->exemplares->count() === 1 ? '1 exemplar' : $titulo->exemplares->count().' exemplares' }}</span>
                            <strong>{{ $titulo->titulo_descricao }}</strong>
                            @if ($titulo->editora)
                                <span>· {{ $titulo->editora->editora_descricao }}</span>
                            @endif
                        </td>
                    </tr>

                    @foreach ($titulo->exemplares as $exemplar)
                        <tr>
                            <td class="number"><strong>{{ $exemplar->exemplar_codigo }}</strong></td>
                            <td>{!! $exemplar->exemplar_subtitulo ? e($exemplar->exemplar_subtitulo) : '<span class="muted">—</span>' !!}</td>
                            <td>{{ $exemplar->exemplar_data?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ $exemplar->armazenagem?->armazenagem_descricao ?? '—' }}</td>
                            <td>{{ $exemplar->tamanho?->tamanho_descricao ?? '—' }}</td>
                            <td class="number">{{ $exemplar->exemplar_situacao ?? '—' }}</td>
                            <td class="number">{{ $exemplar->exemplar_numeropagina ?? '—' }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="empty">Nenhum exemplar cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
