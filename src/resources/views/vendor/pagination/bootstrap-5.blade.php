@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-center">

        {{-- << 最初のページ --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.first')">
            <span class="page-link" aria-hidden="true">&laquo;&laquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->url(1) }}" rel="first" aria-label="@lang('pagination.first')">&laquo;&laquo;</a>
        </li>
        @endif

        {{-- < 前のページ --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="page-link" aria-hidden="true">&laquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo;</a>
        </li>
        @endif

        {{-- ページ番号 --}}
        @foreach ($elements as $element)
        {{-- "三点リーダー" --}}
        @if (is_string($element))
        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- 配列（ページ番号リンク） --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- > 次のページ --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&raquo;</a>
        </li>
        @else
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="page-link" aria-hidden="true">&raquo;</span>
        </li>
        @endif

        {{-- >> 最後のページ --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="last" aria-label="@lang('pagination.last')">&raquo;&raquo;</a>
        </li>
        @else
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.last')">
            <span class="page-link" aria-hidden="true">&raquo;&raquo;</span>
        </li>
        @endif

    </ul>
</nav>
@endif