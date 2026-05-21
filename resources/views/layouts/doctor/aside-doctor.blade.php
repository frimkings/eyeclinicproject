<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
<x-profile />

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->is('doctor/dashboard') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{ route('doctor.all-records') }}" class="nav-link {{ request()->is('doctor/allrecords') ? 'active' : '' }}">

                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>
                    All Records
                  </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('doctor.patient-awaiting') }}" class="nav-link {{ request()->is('doctor/patient-awaiting') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Queue
                  </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('doctor.referrals') }}" class="nav-link {{ request()->is('doctor/referrals*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-paper-plane"></i>
                  <p>
                    Referrals
                  </p>
                </a>
              </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
