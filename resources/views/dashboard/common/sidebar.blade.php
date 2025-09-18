<div class="startbar d-print-none">
    <div class="brand">
        <a href="/dashboard" class="logo">
            <span>
                <img src="{{asset('assets/images/mini.svg')}}" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo-large" class="logo-lg logo-light">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>

    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label mt-2">
                        <span>Main</span>
                    </li>
                    @if (auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="iconoir-report-columns menu-icon"></i>
                                <span>Dashboard</span>
                                <span class="badge text-bg-info ms-auto">New</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/widget">
                            <i class="fas fa-robot menu-icon"></i>
                            <span>Widget</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/train-bot">
                            <i class="iconoir-credit-cards menu-icon"></i>
                            <span>Train Bot</span>
                        </a>

                    @if (auth()->user()->role == 'admin')                        
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/users">
                            <i class="fa-solid fa-user menu-icon"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/subscribers">
                            <i class="fa-solid fa-crown menu-icon"></i>
                            <span>Subscriber</span>
                        </a>
                    </li>
                    @endif    

                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/api-docs">
                            <i class="fa-solid fa-gear menu-icon"></i>
                            <span>Get Widget</span>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</div>
