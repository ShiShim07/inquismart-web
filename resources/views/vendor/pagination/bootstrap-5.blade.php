@if ($paginator->hasPages())
<nav aria-label="Page navigation">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">

        {{-- Results count --}}
        <div style="font-size:13px;color:var(--text-2);">
            Showing
            <span style="font-weight:600;color:var(--text-1);">{{ $paginator->firstItem() }}</span>
            to
            <span style="font-weight:600;color:var(--text-1);">{{ $paginator->lastItem() }}</span>
            of
            <span style="font-weight:600;color:var(--text-1);">{{ $paginator->total() }}</span>
            results
        </div>

        {{-- Page buttons --}}
        <ul style="display:flex;align-items:center;gap:4px;list-style:none;margin:0;padding:0;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1.5px solid var(--border);color:var(--text-3);font-size:13px;cursor:not-allowed;opacity:0.5;font-family:'DM Sans',sans-serif;">
                        &lsaquo;
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1.5px solid var(--border);color:var(--text-2);font-size:13px;text-decoration:none;transition:all 0.15s;font-family:'DM Sans',sans-serif;"
                       onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
                        &lsaquo;
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;font-size:13px;color:var(--text-3);font-family:'DM Sans',sans-serif;">
                            …
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;background:var(--primary);color:#fff;font-size:13px;font-weight:600;font-family:'DM Sans',sans-serif;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1.5px solid var(--border);color:var(--text-2);font-size:13px;text-decoration:none;transition:all 0.15s;font-family:'DM Sans',sans-serif;"
                                   onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                                   onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                       style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1.5px solid var(--border);color:var(--text-2);font-size:13px;text-decoration:none;transition:all 0.15s;font-family:'DM Sans',sans-serif;"
                       onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
                        &rsaquo;
                    </a>
                </li>
            @else
                <li>
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1.5px solid var(--border);color:var(--text-3);font-size:13px;cursor:not-allowed;opacity:0.5;font-family:'DM Sans',sans-serif;">
                        &rsaquo;
                    </span>
                </li>
            @endif

        </ul>
    </div>
</nav>
@endif