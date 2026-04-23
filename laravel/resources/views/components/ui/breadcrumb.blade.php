{{--
    Breadcrumb Bootstrap nativo.
    items: array de ['label' => string, 'url' => string|null, 'active' => bool].
--}}
@props(['items' => []])

@if (! empty($items))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            @foreach ($items as $item)
                <li class="breadcrumb-item @if(!empty($item['active'])) active @endif"
                    @if(!empty($item['active'])) aria-current="page" @endif>
                    @if (empty($item['url']) || ! empty($item['active']))
                        {{ $item['label'] }}
                    @else
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
