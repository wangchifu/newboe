@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- 上一頁按鈕 --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">@lang('上一頁')</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('上一頁')</a>
                </li>
            @endif

            {{-- 下一頁按鈕 --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('下一頁')</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">@lang('下一頁')</span>
                </li>
            @endif
        </ul>
    </nav>
@endif