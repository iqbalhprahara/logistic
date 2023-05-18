@if ($menu['type'] === 'menu')
    <li id="{{ $menu['id'] ?? null }}">
        <a
            href="{{ $menu['url'] ?? 'javascript: void(0);'}}"
            class="waves-effect {{ isset($menu['submenus']) && count($menu['submenus']) > 0 ? 'has-arrow' : null }}"
        >
            @if (isset($menu['icon']))
            <i class="{{ $menu['icon'] }}"></i>
            @endif
            <span key="t-{{ $menu['id'] }}" class="title">{{ $menu['name'] }}</span>
        </a>

        @if (isset($menu['submenus']) && count($menu['submenus']) > 0)
        <ul class="sub-menu" aria-expanded="false">
            {!! $viewMenu($menu['submenus']) !!}
        </ul>
        @endif
    </li>
@else
    <li class="menu-title" key="t-{{ $menu['id'] }}">
        @if (is_null($menu['name']) || empty($menu['name']))
            <hr />
        @else
            {{ $menu['name'] }}
        @endif
    </li>
    @if (isset($menu['submenus']) && count($menu['submenus']) > 0)
        {!! $viewMenu($menu['submenus']) !!}
    @endif
@endif
