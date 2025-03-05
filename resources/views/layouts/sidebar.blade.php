<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces-clipart/pic-1.png') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ strtoupper(Auth::user()->name) }}</span>
                    <span class="text-secondary text-small">{{ strtoupper(Auth::user()->role) }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        <!-- Common Dashboard for All Roles -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <!-- Admin Menu -->
        @if(Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#manageUsers" aria-expanded="false"
                    aria-controls="manageUsers">
                    <span class="menu-title">Manage Users</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account-group menu-icon"></i>
                </a>
                <div class="collapse" id="manageUsers">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.create') }}">Create</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('latest-updates.index') }}">
                    <span class="menu-title">Important Updates</span>
                    <i class="fa fa-bullhorn menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('performance.dashboard') }}">
                    <span class="menu-title">Performance Dashboard</span>
                    <i class="mdi mdi-finance menu-icon"></i>
                </a>
            </li>
        @endif

        <!-- User Menu -->
        @if(Auth::user()->role === 'user' || Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('restaurant.index') }}">
                    <span class="menu-title">Restaurant</span>
                    <i class="mdi mdi-store menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('sliders.index') }}">
                    <span class="menu-title">Sliders</span>
                    <i class="mdi mdi-image menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('contacts.index') }}">
                    <span class="menu-title">Contacts</span>
                    <i class="mdi mdi-account-box menu-icon"></i>
                </a>
            </li>
        @endif
    </ul>
</nav>
