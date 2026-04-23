{{--
    Placeholder legacy — redirige al nuevo empty-state para mantener compat con vistas
    que ya lo usan. Preferir <x-ui.empty-state /> en implementaciones nuevas.
--}}
@props(['message' => 'No hay datos para mostrar.'])
<x-ui.empty-state icon="inbox" title="Sin datos" :message="$message" />
