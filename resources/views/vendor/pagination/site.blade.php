@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Önceki --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>‹</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a></li>
            @endif

            {{-- Sayfa numaraları --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="dots"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Sonraki --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">›</a></li>
            @else
                <li class="disabled"><span>›</span></li>
            @endif
        </ul>
    </nav>
@endif
