<header class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">

        <!-- Sidebar toggle -->
        <button class="btn btn-outline-secondary me-2"
                data-lte-toggle="sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="profile">
           
        </div>
        <div class="ms-auto">
             <a href="{{ route('admin.profile') }}" class="btn btn-warning btn-sm">
                Profile
            </a>

            <a href="{{ route('admin.logout') }}" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>

    </div>

</header>
