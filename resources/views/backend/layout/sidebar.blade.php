 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://rana.my.id">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Rana's <sup>Panel</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @canany(['view permissions'])
   <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwoX"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Permissions</span>
    </a>
    <div id="collapseTwoX" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Roles Permissions:</h6>
            @can('view permissions')
            <a class="collapse-item" href="{{ route('Roles Permissions') }}">View</a>
            @endcan
         
        </div>
    </div>
   </li>    
   @endcanany

   @canany(['manage users', 'create users', 'edit users','delete users'])
   <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwoX0"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Users</span>
    </a>
    <div id="collapseTwoX0" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">User Management:</h6>
            @can('manage users')
            <a class="collapse-item" href="{{ route('User Management') }}">Manage</a>
            @endcan

            @can('create users')
            <a class="collapse-item" href="{{ route('User Create') }}">Create</a>
            @endcan
         
        </div>
    </div>
   </li>    
   @endcanany

    <!-- Heading -->
    <div class="sidebar-heading">
        Ecommerce
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
   @canany(['manage categories', 'create categories', 'edit categories', 'publish categories', 'unpublish categories'])
   <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Categories</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Categories Management:</h6>
            @can('manage categories')
            <a class="collapse-item" href="{{ route('Category Management') }}">Manage</a>
            @endcan
            @can('create catgories')
            <a class="collapse-item" href="{{ route('Category Create') }}">Create</a>
            @endcan
        </div>
    </div>
   </li>    
   @endcanany

   @canany(['manage products', 'create products', 'edit products', 'publish products', 'unpublish products'])
   <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseX1"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Products</span>
    </a>
    <div id="collapseX1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Products Management:</h6>
            @can('manage products')
            <a class="collapse-item" href="{{ route('Product Management') }}">Manage</a>
            @endcan
            @can('createproducts')
            <a class="collapse-item" href="{{ route('Product Create') }}">Create</a>
            @endcan
        </div>
    </div>
   </li>    
   @endcanany

   @canany(['manage orders'])
   <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseX2"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Orders</span>
    </a>
    <div id="collapseX2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Orders Management:</h6>
            @can('manage orders')
            <a class="collapse-item" href="{{ route('Order Management') }}">Manage</a>
            @endcan

        </div>
    </div>
   </li>    
   @endcanany




    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
<!-- End of Sidebar -->