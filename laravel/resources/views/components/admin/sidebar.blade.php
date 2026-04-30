{{--
    Sidebar administrativo. Orden de items y labels tomados de los mockups.
    El item activo se detecta por patrón de ruta.

    Rutas referenciadas definidas en routes/web.php (admin.* / agua.*).
--}}
@php
    /** Helpers locales para mantener el markup plano. */
    $navGroups = [
        [
            'items' => [
                [
                    'route' => 'admin.dashboard',
                    'label' => 'Dashboard',
                    'icon'  => 'grid_view',
                    'match' => 'admin.dashboard',
                ],
                [
                    'route' => 'agua.padron.index',
                    'label' => 'Padrón',
                    'icon'  => 'group',
                    'match' => 'agua.padron.*',
                ],
                [
                    'route' => 'agua.cobros.index',
                    'label' => 'Cobros',
                    'icon'  => 'point_of_sale',
                    'match' => 'agua.cobros.*',
                ],
                [
                    'route' => 'agua.tarifas.index',
                    'label' => 'Tarifas',
                    'icon'  => 'sell',
                    'match' => 'agua.tarifas.*',
                ],
                [
                    'route' => 'agua.multas.index',
                    'label' => 'Multas',
                    'icon'  => 'warning',
                    'match' => ['agua.multas.*', 'agua.multas-usuario.*'],
                ],
                [
                    'route' => 'agua.egresos.index',
                    'label' => 'Egresos',
                    'icon'  => 'receipt_long',
                    'match' => 'agua.egresos.*',
                ],
                [
                    'route' => 'agua.reportes-mensuales.index',
                    'label' => 'Reportes',
                    'icon'  => 'insert_chart',
                    'match' => 'agua.reportes-mensuales.*',
                ],
                [
                    'route' => 'admin.audit.index',
                    'label' => 'Auditoría',
                    'icon'  => 'fact_check',
                    'match' => 'admin.audit.*',
                    'admin_only' => true,
                ],
                [
                    'route' => 'admin.users.index',
                    'label' => 'Usuarios',
                    'icon'  => 'manage_accounts',
                    'match' => 'admin.users.*',
                    'admin_only' => true,
                ],
            ],
        ],
    ];
@endphp

<aside class="agua-sidebar" data-agua-sidebar aria-label="Navegación principal">
    <a href="{{ route('admin.dashboard') }}" class="agua-sidebar__brand">
        <span class="agua-sidebar__brand-mark">
            <span class="material-symbols-outlined is-filled" aria-hidden="true">water_drop</span>
        </span>
        <span class="agua-sidebar__brand-text">
            <span class="agua-sidebar__brand-name d-block">Gestión JASS</span>
            <span class="agua-sidebar__brand-role">Administrador</span>
        </span>
    </a>

    @foreach ($navGroups as $group)
        <ul class="agua-sidebar__nav">
            @foreach ($group['items'] as $item)
                @if (! empty($item['admin_only'] ?? false) && ! auth()->user()->esAdmin())
                    @continue
                @endif
                @php
                    $matchPatterns = is_array($item['match']) ? $item['match'] : [$item['match']];
                    $isActive = collect($matchPatterns)->some(fn ($p) => request()->routeIs($p));
                @endphp
                <li>
                    <a
                        href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                        class="agua-sidebar__link {{ $isActive ? 'is-active' : '' }}"
                        @if($isActive) aria-current="page" @endif
                    >
                        <span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach

    <div class="agua-sidebar__footer">
        @if (Route::has('logout'))
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="agua-sidebar__link w-100 border-0 bg-transparent text-start">
                    <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        @else
            <a href="#" class="agua-sidebar__link">
                <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                <span>Cerrar Sesión</span>
            </a>
        @endif
    </div>
</aside>
