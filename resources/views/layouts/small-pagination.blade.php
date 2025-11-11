@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-center">

        {{-- 上一頁 --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">上一頁</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">上一頁</a></li>
        @endif

        {{-- 1 到 5 --}}
        @php
            $maxMiddle = 5;
            $lastPage  = $paginator->lastPage();
        @endphp

        @for ($i = 1; $i <= min($maxMiddle, $lastPage); $i++)
            @if ($i == $paginator->currentPage())
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor

        {{-- 最後兩頁 --}}
        @if ($lastPage > $maxMiddle + 2)
            <li class="page-item disabled"><span class="page-link">…</span></li>
        @endif

        @foreach ([$lastPage-1, $lastPage] as $page)
            @if ($page > $maxMiddle && $page > 0)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endif
        @endforeach

        {{-- 下一頁 --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">下一頁</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">下一頁</span></li>
        @endif

    </ul>
</nav>
@endif