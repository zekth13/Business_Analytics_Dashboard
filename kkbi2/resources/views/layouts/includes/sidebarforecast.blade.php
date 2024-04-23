<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
            <img src="<?php echo asset('storage/KK_GROUP_LOGO.png') ?>" height="30px" width="80px">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Nav::isRoute('forecast') }}">
        <a class="nav-link" href="{{ route('forecast') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Sales') }}</span></a>
    </li>

    <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Nav::isRoute('storeforecast') }}">
        <a class="nav-link" href="{{ route('storeforecast') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Store') }}</span></a>
    </li>
</ul>
<!-- End of Sidebar -->