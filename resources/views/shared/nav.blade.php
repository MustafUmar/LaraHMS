<nav class="navmenu">
    <div class="navmenu-burger">
        <span class="icon is-medium menu-burger-icon"><i class="fas fa-bars fa-lg"></i></span>
    </div>
    <div class="navmenu-brand">
        <a class="brand-link">
            <div class="navbrand-content">
                <div>
                    <img src="{{url('images/appicon.png')}}" alt="img-bar">
                </div>
                <div>
                    <h4>Admin Dashboard</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="navmenu-right">
        <div class="navmenu-activity">
            <span class="show-right-sidebar has-badge" data-count="84">
                <i class="far fa-hospital symbol fa-lg mr-2"> </i><i class="fas fa-angle-down"></i>
            </span>
            {{-- <span class="has-badge" data-count="84">
                <i class="far fa-bell fa-lg"></i>
            </span> --}}

            <span class="user-dropdown dropdown no-propagate">
                <span class="user-drop-trigger" data-toggle="dropdown" data-reference="parent">
                    <i class="far fa-user fa-lg"></i><i class="fas fa-angle-down"></i>
                </span>

                @switch(Auth::user()->role)
                    @case('Admin')
                        <admin-users-online ref="online_users"></admin-users-online>
                        @break
                    @case('Doctor')
                        <doctors-online ref="online_users"></doctors-online>
                        @break
                    @case('Reception')
                        <doctors-online ref="online_users"></doctors-online>
                        @break
                @endswitch

            </span>
            <form id="logout-form" action="/hpl/logout" method="POST" style="display: none;">
                @csrf
            </form>
            {{-- dropdowns --}}

        </div>
        <div class="navmenu-ellipsis dropdown">
            <button class="ellipsis-btn" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item d-flex justify-content-between align-items-center">
                   Notification
                    <span class="badge badge-primary badge-pill">14</span>
                </a>
                <a href="#" class="dropdown-item">
                    Settings
                </a>
                <a href="#" class="dropdown-item">
                    Logout
                </a>
            </div>
        </div>
    </div>


</nav>
