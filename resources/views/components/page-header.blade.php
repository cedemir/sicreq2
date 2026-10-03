@props(['eyebrow' => null, 'title', 'description' => null])

<div class="mb-8 flex flex-wrap items-end justify-between gap-6">
    <div class="min-w-0">
        @if ($eyebrow)
            <p class="mb-2 text-xs font-bold tracking-[0.2em] text-slate-300 uppercase">{{ $eyebrow }}</p>
        @endif

        <h1 class="text-3xl font-bold tracking-tight text-white">{{ $title }}</h1>

        @if ($description)
            <p class="mt-3 text-[15px] text-slate-400">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex flex-wrap items-center gap-3">
            {{ $actions }}
        </div>
    @endisset
</div>
