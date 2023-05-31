<div>
    <style>
        .table-loading-overlay {
            position: absolute;
            display:contents !important;
            text-align: center;
        }
    </style>
    <div class="row g-3 mb-3">
        <div class="col-xxl-3 col-lg-3">
            <div class="input-group">
                <input type="text" class="form-control" wire:model.debounce.500ms="searchKeywords" placeholder="Search">
                <span class="input-group-text"><i class="bx bx-search-alt"></i></span>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-3">
            <select class="form-control" wire:model="sortBy">
                @foreach ($this->columnDefinition() as $columnDef)
                    @php
                        $sortable = boolval(data_get($columnDef, 'sortable', true)) === true;
                    @endphp
                    @if($sortable)
                    <option value="{{ is_array($columnDef) ? $columnDef['column'] : $columnDef }}" @selected((is_array($columnDef) ? $columnDef['column'] : $columnDef) == $sortBy)>
                        Sort By {{ $columnDef['header'] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', is_array($columnDef) ? $columnDef['column'] : $columnDef)) }}
                    </option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-xxl-3 col-lg-3">
            <select class="form-control" wire:model="sortDir">
                <option value="asc" @selected($sortDir === 'asc')>Sort Ascending</option>
                <option value="desc" @selected($sortDir === 'desc')>Sort Descending</option>
            </select>
        </div>

        @if (count($buttons) > 0)
        <div class="col-xxl-3 col-lg-3 hstack gap-3" style="justify-content:end" wire:ignore>
            @foreach ($buttons as $button)
            {!! $button !!}
            @endforeach
        </div>
        @endif
    </div>
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered mb-0 align-middle text-nowrap">
            <thead class="table-light">
                <tr>
                    @foreach ($this->showedColumn() as $columnDef)
                        @php
                            $width = data_get($columnDef, 'width');
                            $styles = data_get($columnDef, 'styles');
                        @endphp
                        <th @if($width) width="{{ $width }}" @endif @if($styles) style="{{ $styles}}"@endif>{{ $columnDef['header'] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', is_array($columnDef) ? $columnDef['column'] : $columnDef)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody wire:loading wire:loading.class="table-loading-overlay">
                <tr>
                    <td colspan="{{ $this->showedColumnCount() }}">
                        <div class="spinner-grow text-primary m-1" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-primary m-1" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-primary m-1" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-primary m-1" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody wire:loading.remove>
                @if ($results->isEmpty())
                    <tr>
                        <td colspan="{{ $this->showedColumnCount() }}" class="text-center">No data found</td>
                    </tr>
                @else
                    @foreach ($results as $result)
                    @php
                        $result = $this->formatResult($result);
                    @endphp
                        <tr>
                            @foreach ($this->showedColumn() as $columnDef)
                            @php
                                $width = data_get($columnDef, 'width');
                                $styles = data_get($columnDef, 'styles');
                            @endphp
                                @if(strtolower(strval(data_get($columnDef, 'type'))) === 'raw')
                            <td class="{{ strval(data_get($columnDef, 'class', '')) }}" @if($width) width="{{ $width }}" @endif @if($styles) style="{{ $styles}}"@endif>{!! optional($result)[$columnDef['column']] !!}</td>
                                @else
                            <td class="{{ strval(data_get($columnDef, 'class', '')) }}" @if($width) width="{{ $width }}" @endif @if($styles) style="{{ $styles}}"@endif>{{ is_array($columnDef) ? optional($result)[$columnDef['column']] : data_get($result, $columnDef) }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    {{ $results->links() }}
</div>

