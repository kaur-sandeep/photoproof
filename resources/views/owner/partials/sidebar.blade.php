<aside class="app-sidebar bg-dark text-white shadow">
   @php
    $organization = \App\Models\Organization::find(Auth::user()->organization_id);
    @endphp

    <div class="sidebar-brand p-3 text-center">
        <img
            src="{{ !empty($organization?->organization_logo)
                ? asset('storage/' . $organization->organization_logo)
                : asset('user/images/logo-white.png') }}"
            width="200"
            alt="Organization Logo"
            style="max-height: 60px; object-fit: contain;"
        >
    </div>
    <!-- <div class="sidebar-brand p-3 text-center">
        <img src="/user/images/logo-white.png" width="200" height="" alt="header-logo">
    </div> -->

    <div class="sidebar-wrapper px-2">

        <nav>
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('owner.dashboard') }}"
                       class="nav-link text-white">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>

            </ul>

       
  
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('owner.employee') }}"
                       class="nav-link text-white">
                        <i class="bi bi-people me-2"></i>
                        My Employees
                    </a>
                </li>

            </ul>

              
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('owner.photos') }}"
                       class="nav-link text-white">
                        <i class="bi bi-images me-2"></i>
                        Photos
                    </a>
                </li>

            </ul>
             <ul class="nav flex-column">
                <!-- <li class="nav-item"><a href="{{ route('owner.subscription') }}" class="nav-link text-white"><i class="bi bi-credit-card me-2"></i>My Subscription</a></li> -->
                <li class="nav-item"><a href="{{ route('owner.orders') }}" class="nav-link text-white"><i class="bi bi-receipt me-2"></i>Orders</a></li>
                <!-- <li class="nav-item"><a href="{{ route('owner.topup') }}" class="nav-link text-white"><i class="bi bi-plus-circle me-2"></i>Top Up Photos</a></li> -->
            </ul>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('owner.notifications', ['notification_type' => 'upload photo']) }}"
                class="nav-link text-white">
                    <i class="bi bi-bell me-2"></i>
                    Notifications
                </a>
            </li>
        </ul>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('owner.profile') }}"
                       class="nav-link text-white">
                        <i class="bi bi-person me-2"></i>
                        My Profile
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</aside>
