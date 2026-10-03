@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scheme-dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ? "{$title} · " : '' }}{{ config('app.name') }}</title>

        @include('partials.assets')
    </head>
    <body class="min-h-screen bg-navy-950 font-sans text-slate-200 antialiased">
        <div class="flex min-h-screen flex-col lg:flex-row">
            <aside class="flex flex-col border-b border-navy-700 bg-navy-900 lg:sticky lg:top-0 lg:h-screen lg:w-64 lg:shrink-0 lg:border-r lg:border-b-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-6">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white shadow-lg shadow-blue-600/30">
                        {{ Str::upper(Str::substr(config('app.name'), 0, 2)) }}
                    </span>
                    <span class="text-lg font-bold tracking-tight text-white">{{ config('app.name') }}</span>
                </a>

                <nav class="flex-1 space-y-6 overflow-y-auto px-4 pb-6 [scrollbar-color:var(--color-navy-700)_transparent] [scrollbar-width:thin]">
                    <a
                        href="{{ route('dashboard') }}"
                        @class([
                            'flex items-center gap-3 rounded-lg border-l-2 px-4 py-2.5 text-[15px] font-semibold transition',
                            'border-blue-500 bg-navy-800 text-white' => request()->routeIs('dashboard'),
                            'border-transparent text-slate-300 hover:bg-navy-800/60 hover:text-white' => ! request()->routeIs('dashboard'),
                        ])
                    >
                        <span class="w-4 text-center text-xs text-slate-400" aria-hidden="true">▦</span>
                        Painel
                    </a>

                    @foreach (config('menu') as $group => $items)
                        <div>
                            <p class="px-4 pb-2 text-[11px] font-semibold tracking-widest text-slate-500 uppercase">{{ $group }}</p>
                            <ul class="space-y-1">
                                @foreach ($items as $route => $item)
                                    <li>
                                        <a
                                            href="{{ route("{$route}.index") }}"
                                            @class([
                                                'flex items-center gap-3 rounded-lg border-l-2 px-4 py-2 text-sm font-semibold transition',
                                                'border-blue-500 bg-navy-800 text-white' => request()->routeIs("{$route}.*"),
                                                'border-transparent text-slate-300 hover:bg-navy-800/60 hover:text-white' => ! request()->routeIs("{$route}.*"),
                                            ])
                                        >
                                            <span class="w-4 text-center text-xs text-slate-400" aria-hidden="true">{{ $item['icon'] }}</span>
                                            <span class="truncate">{{ $item['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </nav>

                <div class="hidden items-center gap-3 border-t border-navy-700 px-6 py-5 lg:flex">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">{{ auth()->user()->initials() }}</span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="flex h-16 items-center justify-between gap-4 border-b border-navy-700 px-4 sm:px-8">
                    <p class="flex items-center gap-2 text-xs text-slate-300">
                        <span class="size-2 rounded-full bg-green-500"></span>
                        Conectado ao banco {{ basename((string) config('database.connections.'.config('database.default').'.database')) }}
                    </p>

                    <div class="flex items-center gap-3">
                        <span class="hidden text-sm text-slate-300 sm:inline">{{ auth()->user()->name }}</span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-button variant="secondary" class="px-4 py-2">Sair</x-button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-8">
                    <x-flash />

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
