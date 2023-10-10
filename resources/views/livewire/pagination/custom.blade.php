<div class="row justify-content-between align-items-center">
    <div class="col-auto me-auto">
        <p class="text-muted mb-0">Showing <strong>{{ $paginator->total() === 0 ?  0 : ((($paginator->currentPage() - 1) * $paginator->perPage()) + 1) }}</strong> to <strong>{{ (($paginator->currentPage() - 1) * $paginator->perPage()) +  $paginator->count() }}</strong> <span>of</span> <strong>{{ $paginator->total() }}</strong> entries</p>
    </div>
    <div class="col-auto">
        <div class="d-inline-block ms-auto mb-0">
            <div class="p-2">
                @if ($paginator->hasPages())
                <nav aria-label="Page navigation example" class="mb-0">
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <button class="page-link" aria-label="Previous">
                                <span aria-hidden="true">«</span>
                            </button>
                        </li>
                        @else
                        <li class="page-item">
                            <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" class="page-link" aria-label="Previous" id="{{ $paginator->getPageName() }}-previous">
                                <span aria-hidden="true">«</span>
                            </button>
                        </li>
                        @endif
                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled" aria-disabled="true"><button class="page-link">{{ $element }}</button></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <li class="page-item active" wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}" aria-current="page">
                                            <button class="page-link">{{ $page }}</button>
                                        </li>
                                    @else
                                        <li class="page-item" wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                            <button class="page-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" id="{{ $paginator->getPageName() }}-{{ $page }}">{{ $page }}</button>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <li class="page-item">
                                <button class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" aria-label="Next" id="{{ $paginator->getPageName() }}-next">
                                    <span aria-hidden="true">»</span>
                                </button>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true">
                                <button class="page-link" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                </button>
                            </li>
                        @endif
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>
    <!--end col-->
</div>

