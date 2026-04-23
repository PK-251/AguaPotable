{{--
    Data table wrapper. Incluye toolbar y footer (paginación / meta).

    Uso:
        <x-ui.data-table>
            <x-slot name="toolbar">
                <x-ui.search-input placeholder="Código o nombre..." />
                <x-ui.filter-bar> ... </x-ui.filter-bar>
            </x-slot>

            <thead>
                <tr>
                    <th>Código</th><th>Nombre</th><th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row) ... @endforeach
            </tbody>

            <x-slot name="footer">
                <span>Mostrando {{ $paginator->firstItem() }} de {{ $paginator->total() }}</span>
                {{ $paginator->links() }}
            </x-slot>
        </x-ui.data-table>
--}}
@props(['responsive' => true])

<div class="agua-table-card">
    @if (isset($toolbar))
        <div class="agua-table-card__toolbar">
            {{ $toolbar }}
        </div>
    @endif

    <div @class(['table-responsive' => $responsive])>
        <table class="table agua-table mb-0">
            {{ $slot }}
        </table>
    </div>

    @if (isset($footer))
        <div class="agua-table-card__footer">
            {{ $footer }}
        </div>
    @endif
</div>
