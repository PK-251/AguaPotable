{{--
    Topbar del portal del vecino.
    Navegación horizontal, sin sidebar.
--}}
@php
    $portalNav = [
        ['route' => 'portal.home',              'label' => 'Inicio',             'match' => 'portal.home'],
        ['route' => 'portal.estado-cuenta',     'label' => 'Estado de Cuenta',   'match' => 'portal.estado-cuenta'],
        ['route' => 'portal.pagos.index',       'label' => 'Historial de Pagos', 'match' => 'portal.pagos.*'],
        ['route' => 'portal.comprobantes.index','label' => 'Comprobantes',       'match' => 'portal.comprobantes.*'],
    ];
@endphp

<header class="agua-topbar">
    <a href="{{ route('portal.home') }}" class="agua-topbar__brand">J.A.S.S. QUILCATA</a>

    <nav class="agua-portal-nav" aria-label="Menú del portal">
        @foreach ($portalNav as $item)
            @if (Route::has($item['route']))
                <a
                    href="{{ route($item['route']) }}"
                    class="agua-portal-nav__link {{ request()->routeIs($item['match']) ? 'is-active' : '' }}"
                    @if(request()->routeIs($item['match'])) aria-current="page" @endif
                >{{ $item['label'] }}</a>
            @endif
        @endforeach
    </nav>

    <div class="agua-topbar__actions">
        @if (Route::has('logout'))
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-body-secondary px-2" aria-label="Cerrar sesión">
                    <span class="material-symbols-outlined align-middle" aria-hidden="true">logout</span>
                    <span class="d-none d-sm-inline ms-1">Cerrar Sesión</span>
                </button>
            </form>
        @endif
        <x-ui.avatar initials="U" />
    </div>
</header>
