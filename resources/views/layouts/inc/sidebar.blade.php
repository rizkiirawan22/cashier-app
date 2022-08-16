<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Cashier <sup>App</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item{{ (Request::segment(1) == '') ? ' active' : '' }}">
        <a class="nav-link" href="/">
            <i class="fas fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>

    <li class="nav-item{{ (Request::segment(1) == 'barang') ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('barang.index') }}">
            <i class="fas fa-box-open"></i>
            <span>Barang</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Transaksi
    </div>

    <li class="nav-item{{ (Request::segment(1) == 'pembelian' && Request::segment(2) == 'create') ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('pembelian.create') }}">
            <i class="fas fa-cart-arrow-down"></i>
            <span>Pembelian</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Rekap Transaksi
    </div>

    <li class="nav-item{{ (Request::segment(1) == 'pembelian' && Request::segment(2) == '') ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('pembelian.index') }}">
            <i class="fas fa-list"></i>
            <span>Pembelian</span></a>
    </li>

</ul>