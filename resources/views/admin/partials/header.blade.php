<nav class="app-header navbar navbar-expand bg-body" id="navigation" tabindex="-1">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav" role="navigation" aria-label="Navigation 1">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto" role="navigation" aria-label="Navigation 2">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                data-bs-toggle="dropdown">

                    <img src="{{ auth()->user()->profile_image 
                            ? asset('storage/profile/'.auth()->user()->profile_image) 
                            : asset('images/default-user.png') }}"
                        class="user-image rounded-circle shadow me-2"
                        alt="User Image"
                        style="width:35px; height:35px; object-fit:cover;">

                    <span class="d-none d-md-inline">
                        {{ auth()->user()->name }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            Profile
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>