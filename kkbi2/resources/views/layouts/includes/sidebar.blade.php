<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
            <img src="<?php echo asset('storage/KK_GROUP_LOGO.png') ?>" height="30px" width="80px">
        </div>
        <div class="sidebar-brand-text">Sales Analysis</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Nav::isRoute('home') }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Dashboard') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <!--<div class="sidebar-heading">
        {{ __('Sales') }}
    </div>-->

    <!-- Nav Item - Sales -->
    <li class="nav-item {{ Nav::isRoute('sales') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSales">
            <i class="fas fa-fw fa-dollar-sign"></i> Sales
        </a>
        <div id="collapseSales" class="collapse" aria-labelledby="headingSales" data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('sales') }}">{{ __('Sales Summary') }}</a>
                <a class="collapse-item" href="{{ route('outlets') }}">{{ __('Outlets') }}</a>
                <a class=" collapse-item" href="{{ route('products') }}"">{{ __('Product Sales') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Supplier -->
    <li class=" nav-item {{ Nav::isRoute('sales') }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSupplier">
                        <i class="fas fa-fw fa-building"></i> Supplier
                    </a>
                    <div id="collapseSupplier" class="collapse" aria-labelledby="headingSupplier" data-parent="#accordionSidebar" style="">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('suppliers') }}">{{ __('Supplier Products') }}</a>
                        </div>
                    </div>
    </li>

    <!-- Nav Item - Inventory -->
    <li class="nav-item {{ Nav::isRoute('sales') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory">
            <i class="fas fa-fw fa-box-open"></i> Inventory
        </a>
        <div id="collapseInventory" class="collapse" aria-labelledby="headingInventory" data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('inventory') }}">{{ __('Inventory') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Reporting -->
    <li class="nav-item {{ Nav::isRoute('reports') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReporting">
            <i class="fas fa-fw fa-dollar-sign"></i> Reporting
        </a>
        <div id="collapseReporting" class="collapse" aria-labelledby="headingReporting" data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('reports') }}">{{ __('Create Report') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Warehouse -->
    <li class="nav-item {{ Nav::isRoute('sales') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseWarehouse">
            <i class="fas fa-fw fa-store-alt"></i> Warehouse
        </a>
        <div id="collapseWarehouse" class="collapse" aria-labelledby="headingWarehouse" data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--<a class="collapse-item" href="{{ route('sales') }}">{{ __('Sales Illustration') }}</a>-->
                <a class="collapse-item" href="#">{{ __('Coming soon') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Warehouse -->
    <li class="nav-item {{ Nav::isRoute('sales') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePromotion">
            <i class="fas fa-fw fa-store-alt"></i> Promotion
        </a>
        <div id="collapsePromotion" class="collapse" aria-labelledby="headingPromotion" data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--<a class="collapse-item" href="{{ route('sales') }}">{{ __('Sales Illustration') }}</a>-->
                <a class="collapse-item" href="#">{{ __('Coming soon') }}</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->