{{--
    Pagination bar. Se espera un LengthAwarePaginator.
    Usa Bootstrap 5 (Paginator::useBootstrapFive() en AppServiceProvider).
--}}
@props(['paginator' => null, 'label' => 'registros'])

@if ($paginator && $paginator->total() > 0)
    <div class="text-body-secondary small">
        Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }}
        de {{ number_format($paginator->total()) }} {{ $label }}
    </div>
    <div>
        {{ $paginator->withQueryString()->onEachSide(1)->links() }}
    </div>
@endif
