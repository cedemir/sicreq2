@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-400">
            {!! __('Showing') !!}
            <span class="font-semibold text-slate-200">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="font-semibold text-slate-200">{{ $paginator->lastItem() }}</span>
            {!! __('of') !!}
            <span class="font-semibold text-slate-200">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        </p>

        <ul class="flex flex-wrap items-center gap-1 text-sm">
            @php($linkClasses = 'flex h-9 min-w-9 items-center justify-center rounded-lg border px-3 transition')

            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $linkClasses }} border-navy-700 text-slate-600" aria-disabled="true">{!! __('pagination.previous') !!}</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkClasses }} border-navy-700 bg-navy-950 text-slate-200 hover:bg-navy-800">{!! __('pagination.previous') !!}</a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="{{ $linkClasses }} border-transparent text-slate-500">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $linkClasses }} border-blue-600 bg-blue-600 font-semibold text-white" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $linkClasses }} border-navy-700 bg-navy-950 text-slate-200 hover:bg-navy-800">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkClasses }} border-navy-700 bg-navy-950 text-slate-200 hover:bg-navy-800">{!! __('pagination.next') !!}</a>
                @else
                    <span class="{{ $linkClasses }} border-navy-700 text-slate-600" aria-disabled="true">{!! __('pagination.next') !!}</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
