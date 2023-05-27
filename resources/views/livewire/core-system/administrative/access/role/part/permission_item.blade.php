@php
$uniq = uniqid();
@endphp

<li
    class="{{ isset($menu['gate']) ? 'has-permission' : null }} {{ isset($menu['submenus']) && count($menu['submenus']) > 0 ? 'has-sub' : '' }}">
    <div class="d-flex justify-content-between align-items-center py-3">
        <div class="form-check">
            @if(isset($menu['gate']))
            <input class="form-check-input" type="checkbox"
                {{ in_array($menu['gate'], $role->permissions->pluck('name')->toArray() ?? []) ? 'checked' : '' }} value="{{ $menu['gate'] }}"
                name="gates[]" id="{{ $menu['gate'] }}" wire:click.prevent="togglePermission('{{$menu['gate']}}')">
            @endif
            <label class="form-check-label" for="{{ $menu['gate'] }}">
                <h6 class="m-0">
                    {{ (is_null($menu['name']) || empty($menu['name'])) && ($menu['type'] ?? 'menu') === 'divider' ? 'Divider Line' : $menu['name'] }}</h6>
            </label>
            <div>
                @if (($menu['type'] ?? 'menu') === 'menu')
                    <small class="text-muted">{{ optional($menu)['description'] }}</small>
                @else
                    @if (is_null($menu['name']) || empty($menu['name']))
                        <small class="text-muted">Menu divider will appear as a line</small>
                    @else
                        <small class="text-muted">Menu divider will appear with name {{ $menu['name'] }}</small>
                    @endif
                @endif
            </div>
        </div>
    </div>


    @if (isset($menu['permissions']) && count($menu['permissions']) > 0 && ($menu['type'] ?? 'menu') === 'menu')
        <div class="collapse show" id="permission-{{ $uniq }}">
            <h6 class="text-primary">Permissions</h6>
            <ul class="text-light rounded">
                @foreach ($menu['permissions'] as $item)
                    <li class="py-3 form-check">
                        <input class="form-check-input" type="checkbox"
                            {{ in_array($item['gate'], $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }}
                            value="{{ $item['gate'] }}" name="gates[]" id="{{ $item['gate'] }}" wire:click.prevent="togglePermission('{{$item['gate']}}')">
                        <label class="form-check-label" for="{{ $item['gate'] }}">
                            <h6 class="m-0">{{ $item['title'] }}</h6>
                        </label>
                        <div>
                            <small class="text-muted">{{ $item['description'] }}</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (isset($menu['submenus']) && count($menu['submenus']) > 0)
        <ul class="pb-3">
            {!! $viewMenu($menu['submenus'], $level + 1) !!}
        </ul>
    @endif
</li>
