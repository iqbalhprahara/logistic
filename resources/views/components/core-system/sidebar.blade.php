@php
$viewMenu = function ($menus) use (&$viewMenu) {
    $html = '';
    foreach ($menus as $menu) {
        $html .= view('components.core-system.parts.menu_item', ['menu' => $menu, 'viewMenu' => $viewMenu]);
    }
    return $html;
};
@endphp

<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu" data-turbolinks-permanent>
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {!! $viewMenu($menus) !!}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
