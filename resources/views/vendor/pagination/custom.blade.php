@if ($paginator->hasPages())
<nav class="custom-pagination" role="navigation">
    <ul>
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled">&laquo;</li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" data-page="{{ $paginator->currentPage()-1 }}">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled">{{ $element }}</li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active">{{ $page }}</li>
                    @else
                        <li><a href="{{ $url }}" data-page="{{ $page }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" data-page="{{ $paginator->currentPage()+1 }}">&raquo;</a></li>
        @else
            <li class="disabled">&raquo;</li>
        @endif
    </ul>
</nav>
@endif
<style>
    .custom-pagination ul {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.custom-pagination li {
    padding: 5px 10px;
    border: 1px solid #ddd;
    cursor: pointer;
}

.custom-pagination li.active {
    background-color: #2563eb;
    color: #fff;
    font-weight: bold;
}

.custom-pagination li.disabled {
    color: #888;
    cursor: not-allowed;
}

.custom-pagination li a {
    text-decoration: none;
    color: inherit;
}

</style>