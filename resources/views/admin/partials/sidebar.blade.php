<aside class="app-sidebar bg-dark text-white shadow">

    <div class="sidebar-brand p-3 text-center">
        <img src="/user/images/logo-white.png" width="200" height="" alt="header-logo">
    </div>

    <div class="sidebar-wrapper px-2">

        <nav>
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link text-white">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>

            </ul>
            @if(auth()->check() && auth()->user()->getRoleNames()->contains('super-admin'))
  
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.users.data') }}"
                       class="nav-link text-white">
                        <i class="bi bi-people me-2"></i>
                        Admin Users
                    </a>
                </li>

            </ul>
            @endif
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                       class="nav-link text-white">
                        <i class="bi bi-people me-2"></i>
                        App Users
                    </a>
                </li>

            </ul>

              <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.photos') }}"
                       class="nav-link text-white">
                        <i class="bi bi-images me-2"></i>
                        Photos
                    </a>
                </li>

            </ul>

            @if(auth()->check() && auth()->user()->getRoleNames()->contains('super-admin'))
              <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}"
                       class="nav-link text-white">
                        <i class="bi bi-gear-fill me-2"></i>
                        Settings
                    </a>
                </li>

            </ul>
            @endif
              <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.activity') }}"
                       class="nav-link text-white">
                          <i class="bi bi-clock-history me-2"></i>
                        Activity Logs
                    </a>
                </li>

            </ul>
            @if(auth()->check() && auth()->user()->getRoleNames()->contains('super-admin'))
              <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.reported') }}"
                       class="nav-link text-white">
                          <i class="bi bi-flag me-2"></i>
                        Reported Images
                    </a>
                </li>

            </ul>
            @endif
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('notifications.index') }}"
                       class="nav-link text-white">
                          <i class="bi bi-bell me-2"></i>
                        Notifications
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</aside>
