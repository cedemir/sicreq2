@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scheme-dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ? "{$title} · " : '' }}{{ config('app.name') }}</title>

        @include('partials.assets')
    </head>
    <body class="flex min-h-screen items-center justify-center bg-navy-950 bg-[radial-gradient(ellipse_at_top,var(--color-navy-800),transparent_60%)] px-4 py-12 font-sans text-slate-200 antialiased">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-center gap-4 text-center">
                <span class="flex size-14 items-center justify-center rounded-2xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-600/30">
                    {{ Str::upper(Str::substr(config('app.name'), 0, 2)) }}
                </span>
                <div>
                    <p class="text-2xl font-bold tracking-tight text-white">{{ config('app.name') }}</p>
                    <p class="mt-1 text-sm text-slate-400">Catálogo de quadrinhos</p>
                </div>
            </div>

            {{ $slot }}
        </div>
    </body>
</html>
