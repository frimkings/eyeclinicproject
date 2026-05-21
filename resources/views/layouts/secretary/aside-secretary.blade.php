<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <x-profile />

  <div class="sidebar">
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="{{ route('secretary.dashboard') }}"
          class="nav-link {{ request()->is('secretary/dashboard') ? 'active' : '' }}">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Dashboard
          </p>
        </a>
      </li>



      <li class="nav-item">
        <a href="{{ route('secretary.patients') }}"
          class="nav-link {{ request()->is('secretary/patients') ? 'active' : '' }}">
          <i class="fas fa-people-arrows"></i>
          <p>
            Patients
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('secretary.patient-clearance') }}"
          class="nav-link {{ request()->is('secretary/patient-clearance') ? 'active' : '' }}">
          <i class="fas fa-book"></i>
          <p>
            Clearance
          </p>
        </a>
      </li>
  

      <li class="nav-item">
        <a href="{{ route('secretary.spectacles') }}"
          class="nav-link {{ request()->is('secretary/spectacles') ? 'active' : '' }}">
          <i class="fas fa-glasses"></i>
          <p>
            Spectacles
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('cashier.seller-desk') }}"
          class="nav-link {{ request()->is('cashier/seller-desk') ? 'active' : '' }}">
          <i class="fas fa-shopping-cart"></i>
          <p>
            Store
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('cashier.outstanding-balances') }}"
          class="nav-link {{ request()->is('cashier/outstanding-balances*') ? 'active' : '' }}">
          <i class="nav-icon fas fa-clock"></i>
          <p>Outstanding Balances</p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('cashier.sales-records') }}"
          class="nav-link {{ request()->is('cashier/sales-records') ? 'active' : '' }}">
          <i class="fas fa-shopping-cart"></i>
          <p>
            Sales
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('secretary.appointments') }}"
          class="nav-link {{ request()->routeIs('secretary.appointments') ? 'active' : '' }}">
          <i class="nav-icon fas fa-calendar-alt"></i>
          <p>Appointments</p>
        </a>
      </li>

    </ul>
  </nav>
  <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>