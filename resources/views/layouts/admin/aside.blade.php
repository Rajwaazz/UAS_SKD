  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ url('admin/dashboard') }}">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard</span>
              </a>
          </li><!-- End Dashboard Nav -->

          <li class="nav-heading">Pages</li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="users-profile.html">
                  <i class="bi bi-person"></i>
                  <span>User</span>
              </a>
          </li><!-- End User Page Nav -->

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ url('admin/movies') }}">
                  <i class="bi bi-film"></i>
                  <span>Movie</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ url('admin/schedules') }}">
                  <i class="bi bi-calendar-event"></i>
                  <span>Jadwal</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ url('admin/backups') }}">
                  <i class="bi bi-database"></i>
                  <span>Backup dan Restore</span>
              </a>
          </li><!-- End Role Page Nav -->
      </ul>

  </aside><!-- End Sidebar-->
