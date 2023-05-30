<div class="col-{{ $col }}">
    <div {{ $attributes->merge(['class' => 'card ' . $class]) }}>
        @if (isset($header))
            <div {{ $header->attributes->class('card-header bg-transparent') }}>
                {{ $header ?? null }}
            </div>
        @endif

        @if (isset($flat))
            {{ $flat ?? null }}
        @endif

        @if (isset($button))
            <div class="card-body border-bottom">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        {{ $button }}
                    </div>
                </div>
            </div>
        @endif

        @if (isset($body))
            <div class="card-body ">
                {{ $body ?? null }}
            </div>
        @endif

        @if (isset($footer))
            <div {{ $footer->attributes->class('card-footer bg-transparent') }}>
                {{ $footer ?? null }}
            </div>
        @endif

    </div>
</div>

