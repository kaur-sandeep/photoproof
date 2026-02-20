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
          

<!-- Notification Bell -->


          <ul class="navbar-nav ms-auto" role="navigation" aria-label="Navigation 2">

<div style="position: relative; display: inline-block; cursor: pointer;" id="notificationBell">
  <i class="bi bi-bell" style="font-size: 24px;"></i> <!-- Bootstrap Icons bell -->
  <span id="notificationCount" style="position: absolute; top: -6px; right: -6px; background: red; color: white; font-size: 12px; border-radius: 50%; padding: 2px 6px; display: none;">0</span>
</div>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                data-bs-toggle="dropdown">

                    <img src="{{ auth()->user()->profile_image 
                            ? asset('storage/profile/'.auth()->user()->profile_image) 
                            : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
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


      <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="notificationModalBody">
        <!-- Notification items will load here -->
        <p>Loading notifications...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         <a href="{{ route('notifications.index') }}" class="btn btn-primary">
        Show All Notifications
    </a>
      </div>
    </div>
  </div>
</div>
      