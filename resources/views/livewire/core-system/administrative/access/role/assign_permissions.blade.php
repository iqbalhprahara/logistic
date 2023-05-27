@php
$viewMenu = function ($menus, $level) use (&$viewMenu, $role) {
    $html = '';
    foreach ($menus as $menu) {
        $html .= view('livewire.core-system.administrative.access.role.part.permission_item', ['level' => $level, 'role' => $role, 'menu' => $menu, 'viewMenu' => $viewMenu]);
    }
    return $html;
};
@endphp
<x-core-system.modal id="modal-assign-role-{{ $role->id }}" wire:ignore.self scrollable=true size="fullscreen">
    <x-slot name="title">
        Assign Role Permissions
    </x-slot>
    <x-slot name="body">
        <hr>
        <ul class="mb-3">
            {!! $viewMenu($menus, 1) !!}
        </ul>
    </x-slot>
    <x-slot name="footer">
        <x-core-system.button type="button" color="primary" wire:click.prevent="toggleAllPermissions">Select / Deselect All Permissions</x-core-system.button>
        <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
    </x-slot>
</x-core-system.modal>
