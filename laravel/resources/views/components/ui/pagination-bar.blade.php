@props(['paginator' => null])
@if($paginator)
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-body-secondary small">
            Mostrando {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}
        </div>
        <div>
            {{ $paginator->withQueryString()->links() }}
        </div>
    </div>
@endif
