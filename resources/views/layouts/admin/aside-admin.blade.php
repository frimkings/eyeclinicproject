<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
<x-profile />

   

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Clinic
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="{{ route('doctor.patient-awaiting') }}" class="nav-link {{ request()->is('doctor/patient-awaiting') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Patients
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
                <a href="{{ route('admin.diagnoses') }}" class="nav-link {{ request()->is('admin/diagnoses') ? 'active' : '' }}">

                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>
                    Diagnoses
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Store
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.category') }}" class="nav-link {{ request()->is('admin/category') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Category
                    </p>
                  </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.product') }}" class="nav-link {{ request()->is('admin/product') ? 'active' : '' }}">

                  <i class="far fa-circle nav-icon"></i>
                  <p>Product</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.inventory-alerts') }}" class="nav-link {{ request()->is('admin/inventory-alerts') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Inventory Alerts</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.stock-movements') }}" class="nav-link {{ request()->is('admin/stock-movements') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-success"></i>
                  <p>Stock Receiving</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.suppliers') }}" class="nav-link {{ request()->is('admin/suppliers') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>Suppliers</p>
                </a>
              </li>

              @hasrole('Super Admin')
              <li class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Settings</p>
                </a>
              </li>
              @endhasrole
             
             


            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Finances
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                {{-- <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->is('admin/reports') ? 'active' : '' }}"> --}}

                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales</p>
                </a>
              </li>
           
           
              <li class="nav-item">
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->is('admin/reports') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reports</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.income-statement') }}" class="nav-link {{ request()->is('admin/income-statement*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-success"></i>
                  <p>Income Statement</p>
                </a>
              </li>
                <li class="nav-item">
                <a href="{{ route('admin.expenses') }}" class="nav-link {{ request()->is('admin/expenses*') ? 'active' : '' }}">
                  <i class="far fa-circle 
                  nav-icon text-success"></i>
                  <p>Expenses</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.daily-cash-summary') }}" class="nav-link {{ request()->is('admin/daily-cash-summary*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>Daily Cash Summary</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.lens-outstanding-report') }}" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Lens Outstanding PDF</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('cashier.outstanding-balances') }}" class="nav-link {{ request()->is('cashier/outstanding-balances*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Outstanding Balances</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.approvals') }}" class="nav-link {{ request()->is('admin/approvals*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>
                    Approvals
                    @php
                      $totalPending = \App\Models\DiscountApprovalRequest::where('status', 'pending')->count()
                                    + \App\Models\RefundLog::pendingCount()
                                    + \App\Models\ClearanceRevokeLog::pendingCount()
                                    + \App\Models\PasswordResetRequest::pendingCount();
                    @endphp
                    @if($totalPending > 0)
                      <span class="badge badge-warning right">{{ $totalPending }}</span>
                    @endif
                  </p>
                </a>
              </li>

            </ul>
          </li>
         


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Employees
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.roles-permissions') }}" class="nav-link {{ request()->is('admin/roles-permissions') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-primary"></i>
                  <p>Roles &amp; Permissions</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.login-history') }}" class="nav-link {{ request()->is('admin/login-history') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>Login History</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.audit-trail') }}" class="nav-link {{ request()->is('admin/audit-trail') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Audit Trail</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.sms-logs') }}" class="nav-link {{ request()->is('admin/sms-logs') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>SMS Logs</p>
                </a>
              </li>

              {{-- Password Resets link merged into Approvals page --}}
             
            </ul>
          </li>
        
        


        </ul>
      </nav>

      @php $backupDay = now()->dayOfWeek === 1; @endphp
      <div class="sidebar-custom p-2">
        <div class="alert alert-{{ $backupDay ? 'warning' : 'secondary' }} py-1 px-2 mb-0 small"
             style="font-size:0.72rem; border-radius:4px;">
          <i class="fas fa-database mr-1"></i>
          @if($backupDay)
            <strong>Today is backup day.</strong> Please export a database backup before you finish.
          @else
            Next backup: <strong>Monday</strong>. Backup via phpMyAdmin &rsaquo; Export.
          @endif
        </div>
      </div>
  </aside>
