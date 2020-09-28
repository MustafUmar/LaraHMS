<div class="sidebar-nav">
    <div class="sidemenu-content">
        <ul class="sidemenu-list">
                <li>
                    <router-link to="/hpl/dashboard" class="sidemenu-link">
                        <i class="side-icon fas fa-user"></i>
                        Dashboard
                    </router-link>
                </li>
            </ul>
    </div>
   {{-- <h5 class="sidenav-header">Patients</h5> --}}
   <div class="sidemenu-content">
       <ul class="sidemenu-list">
            <li>
                <a class="sidemenu-link">
                    <i class="side-icon fas fa-user"></i>
                    Patient
                </a>
            </li>
            <li>
                <a class="sidemenu-link">
                    <i class="side-icon fas fa-users"></i>
                    Patient files
                </a>
            </li>
        </ul>
   </div>

   {{-- <h5 class="sidenav-header">Appointment</h5> --}}
   <div class="sidemenu-content">
       <ul class="sidemenu-list">
            <li>
                <router-link to="/hpl/doctors" class="sidemenu-link">
                    <i class="side-icon fas fa-user"></i>
                    Doctors
                </router-link>
            </li>
            <li>
                <a class="sidemenu-link">
                    <i class="side-icon fas fa-users"></i>
                    Appointments
                </a>
            </li>
            <li>
                <router-link to="/hpl/receptions" class="sidemenu-link">
                    <i class="side-icon fas fa-users"></i>
                    Reception
                </router-link>
            </li>
            <li>
                <router-link to="/hpl/finances" class="sidemenu-link">
                    <i class="side-icon fas fa-file-invoice"></i>
                    Finances
                </router-link>
            </li>
            <li>
                <a class="sidemenu-link">
                    <i class="side-icon fas fa-users"></i>
                    Nurses
                </a>
            </li>
            <li>
                <router-link to="/hpl/staffs" class="sidemenu-link">
                    <i class="side-icon fas fa-users"></i>
                    Staffs
                </router-link>
            </li>
        </ul>
   </div>

</div>
