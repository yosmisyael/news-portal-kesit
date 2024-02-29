<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('logo.webp') }}" height="40px" width="40px" alt="logo">
        </div>
        <div class="sidebar-brand-text mx-3 text-white">Dashboard</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Route::currentRouteNamed('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Management
    </div>

    {{-- User Menu --}}
    <li class="nav-item {{ Route::is('admin.user.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
            aria-expanded="true" aria-controls="collapseUser">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>User</span>
        </a>
        <div id="collapseUser" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.user.create') }}">Create a new User</a>
                <a class="collapse-item" href="{{ route('admin.user.index') }}">User List</a>
            </div>
        </div>
    </li>

    {{-- Post Menu --}}
    <li class="nav-item {{ Route::is('admin.post.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePost"
            aria-expanded="true" aria-controls="collapsePost">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Post</span>
        </a>
        <div id="collapsePost" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.submission.index') }}">Post Submissions</a>
                <a class="collapse-item" href="{{ route('admin.post.index') }}">Post List</a>
            </div>
        </div>
    </li>

    {{-- Tag Menu --}}
    <li class="nav-item {{ Route::is('admin.category.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategory"
            aria-expanded="true" aria-controls="collapseCategory">
            <i class="fas fa-fw fa-tags"></i>
            <span>Category</span>
        </a>
        <div id="collapseCategory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.category.create') }}">Create a new Category</a>
                <a class="collapse-item" href="{{ route('admin.category.index') }}">Tag List</a>
            </div>
        </div>
    </li>

    {{-- Headline Menu --}}
    <li class="nav-item {{ Route::is('admin.headline.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{ route('admin.headline.index') }}">
            <i class="fas fa-fw fa-fire"></i>
            <span>Headline</span>
        </a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
