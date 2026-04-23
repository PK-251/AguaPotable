{{--
    Filter bar — contenedor para combinar búsqueda + selects en cabecera de tabla.
    Se pega visualmente a la tabla que le sigue.

    Uso:
        <x-ui.filter-bar>
            <x-ui.search-input label="Buscar" name="q" placeholder="Código o Nombre..." />
            <div>
                <label class="form-label">Estado</label>
                <select class="form-select"><option>Todos</option></select>
            </div>
        </x-ui.filter-bar>
--}}
<div {{ $attributes->merge(['class' => 'agua-filter-bar']) }}>
    {{ $slot }}
</div>
