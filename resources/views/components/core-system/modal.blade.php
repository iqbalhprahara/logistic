@props(['id' => '', 'size' => 'md', 'scrollable' => false, 'headerClass' => '', 'closeButton' => true])
<style>
    .modal-header.bg-primary .modal-title{
        color: white;
    }
</style>
<div {{ $attributes->merge(['class' => 'modal fade', 'id' => $id]) }} tabindex="-1"
    aria-labelledby="{{ $id }}" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} {{$scrollable ? 'modal-dialog-scrollable' : ''}}">
        <div class="modal-content">
            <div class="modal-header {{$headerClass}} border-bottom-0">
                <h5 class="modal-title" id="{{ $id }}">
                    {{ $title ?? 'Modal Title' }}
                </h5>
                @if(!$closeButton === 'false')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                {{ $body ?? null }}
            </div>
            @if (isset($footer))
                <div class="modal-footer border-top-0">
                    {{ $footer ?? null }}
                </div>
            @endif
        </div>
    </div>
</div>
