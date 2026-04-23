{{--
    Topbar administrativa.
    Secciones:
      - Toggle móvil del sidebar
      - Marca "J.A.S.S. QUILCATA" + breadcrumb opcional
      - Buscador global (placeholder)
      - Íconos rápidos (notificaciones, configuración) + avatar
--}}
@php
    $breadcrumb = $topBreadcrumb ?? ($topBreadcrumb = []);
    $user = auth()->user();
    $initials = $user
        ? strtoupper(substr(preg_replace('/\s+/', '', $user->name ?? 'A'), 0, 1))
        : 'A';
@endphp

<header class="agua-topbar">
    <button
        type="button"
        class="agua-topbar__toggle"
        aria-label="Abrir menú"
        data-agua-sidebar-toggle
    >
        <span class="material-symbols-outlined" aria-hidden="true">menu</span>
    </button>

    <a href="{{ route('admin.dashboard') }}" class="agua-topbar__brand">J.A.S.S. QUILCATA</a>

    @if (!empty($breadcrumb))
        <nav class="agua-topbar__breadcrumb d-none d-md-flex" aria-label="breadcrumb">
            @foreach ($breadcrumb as $i => $crumb)
                @if (!empty($crumb['url']) && empty($crumb['active']))
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                @else
                    <span class="{{ !empty($crumb['active']) ? 'is-current' : '' }}">{{ $crumb['label'] }}</span>
                @endif
                @if ($i < count($breadcrumb) - 1)
                    <span class="agua-topbar__sep material-symbols-outlined is-sm" aria-hidden="true">chevron_right</span>
                @endif
            @endforeach
        </nav>
    @endif

    <label class="agua-topbar__search d-none d-sm-block" role="search">
        <span class="agua-topbar__search-icon material-symbols-outlined is-sm" aria-hidden="true">search</span>
        <input type="search" placeholder="Buscar..." aria-label="Buscar">
    </label>

    <div class="agua-topbar__actions">
        <button type="button" class="agua-icon-btn" aria-label="Notificaciones">
            <span class="material-symbols-outlined" aria-hidden="true">notifications</span>
            <span class="agua-icon-btn__dot" aria-hidden="true"></span>
        </button>
        <button type="button" class="agua-icon-btn" aria-label="Configuración">
            <span class="material-symbols-outlined" aria-hidden="true">settings</span>
        </button>
        <x-ui.avatar :initials="$initials" />
    </div>
</header>
