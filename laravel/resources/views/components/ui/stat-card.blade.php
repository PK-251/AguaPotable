@props(['title' => '', 'value' => '—', 'help' => null])
<div class="card">
    <div class="card-body">
        <p class="text-body-secondary text-uppercase small mb-1">{{ $title }}</p>
        <p class="h4 mb-0">{{ $value }}</p>
        @if($help)
            <p class="small text-body-secondary mt-1 mb-0">{{ $help }}</p>
        @endif
    </div>
</div>
