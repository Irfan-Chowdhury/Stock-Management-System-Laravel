    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
          </div>
          <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
        </a>
  
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
  
        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <a class="nav-link" href="{{route('dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>
  
        <!-- Divider -->
        <hr class="sidebar-divider">
  
        <!-- Heading -->
        {{-- <div class="sidebar-heading">
          Interface
        </div> --}}
  
        <!-- Category Setup -->
        <li class="nav-item">
          <a class="nav-link" href="{{route('category.index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Category Setup</span></a>
        </li>
        
        <!-- Company Setup -->
        <li class="nav-item">
          <a class="nav-link" href="{{route('company.index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Company Setup</span></a>
        </li>
        
        <!-- Item Setup -->
        <li class="nav-item">
          <a class="nav-link" href="{{route('item.create')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Item Setup</span></a>
        </li>
        
        <!-- Stock In -->
        <li class="nav-item">
          <a class="nav-link" href="{{route('stockin.create')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Stock In</span></a>
        </li>

         <!-- Stock Out -->
         <li class="nav-item">
          <a class="nav-link" href="{{route('stockout.create')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Stock Out</span></a>
        </li>
  
        <!-- Search & View Items -->
        <li class="nav-item">
          <a class="nav-link" href="{{route('searchAndViewItems.index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Search & View Items</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
  
        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
  
      </ul>
      <!-- End of Sidebar -->