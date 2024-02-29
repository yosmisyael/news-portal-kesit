<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('user.dashboard', ['username' => '@' . $user->username]) }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('logo.webp') }}" height="40px" width="40px" alt="logo">
        </div>
        <div class="sidebar-brand-text mx-3 text-white">Dashboard</div>
    </a>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Route::currentRouteNamed('user.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.dashboard', ['username' => '@' . $user->username]) }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Article
    </div>

    <!-- Nav Item -->
    <li class="nav-item {{ Route::currentRouteNamed('user.post.create') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.post.create', ['username' => '@' . $user->username]) }}">
        <i class="fas fa-fw fa-plus-circle"></i>
        <span>Write new Post</span></a>
    </li>
    
    <!-- Nav Item -->
    <li class="nav-item {{ Route::currentRouteNamed('user.post.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.post.index', ['username' => '@' . $user->username]) }}">
        <i class="fas fa-fw fa-layer-group"></i>
        <span>Your Post List</span></a>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Profile -->
    <div class="sidebar-heading">
        Profile
    </div>
    
    <li class="nav-item {{ Route::currentRouteNamed('user.profile.show') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.profile.show', ['username' => '@' . $user->username]) }}">
            <i class="fas fa-fw fa-id-card"></i>
            <span>View Profile</span></a>
    </li>

    <li class="nav-item {{ Route::currentRouteNamed('user.profile.edit') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.profile.edit', ['username' => '@' . $user->username]) }}">
        <i class="fas fa-fw fa-user-cog"></i>
        <span>Update Profile</span></a>
    </li>
    
    <li class="nav-item {{ Route::currentRouteNamed('user.profile.reset') ? 'active' : '' }}">
        <a class="nav-link active" href="{{ route('user.profile.reset', ['username' => '@' . $user->username]) }}">
        <i class="fas fa-fw fa-key"></i>
        <span>Reset Password</span></a>
    </li>
    
    <hr class="sidebar-divider d-none d-md-block">

    {{-- sidebar toggle --}}
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->