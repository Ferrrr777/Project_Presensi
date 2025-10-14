<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Kawai Musik Pontianak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4f46e5; /* Indigo untuk tema musik yang kreatif */
      --secondary-color: #7c3aed; /* Violet untuk aksen */
      --sidebar-bg: #f8fafc; /* Abu-abu muda untuk light mode */
      --sidebar-hover: #e2e8f0;
      --topbar-bg: #ffffff; 
      --topbar-shadow: rgba(0, 0, 0, 0.1);
      --content-bg: #ffffff;
      --text-primary: #1e293b;
      --text-secondary: #64748b;
      --border-color: #e2e8f0;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] {
      --sidebar-bg: #1e293b;
      --sidebar-hover: #334155;
      --topbar-bg: #0f172a;
      --content-bg: #0f172a;
      --text-primary: #f1f5f9;
      --text-secondary: #94a3b8;
      --border-color: #334155;
      --topbar-shadow: rgba(0, 0, 0, 0.3);
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--content-bg);
      color: var(--text-primary);
      transition: var(--transition);
      margin: 0;
      line-height: 1.6;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background-color: var(--sidebar-bg);
      min-height: 100vh;
      padding: 1.5rem 0;
      overflow-y: auto;
      transition: var(--transition);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
      border-right: 1px solid var(--border-color);
      box-shadow: var(--shadow-md);
    }

    .sidebar-header {
      padding: 0 1.5rem 2rem;
      text-align: center;
      border-bottom: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .sidebar-header h4 {
      margin: 0 0 1rem;
      font-weight: 700;
      color: var(--text-primary);
      font-size: 1.25rem;
    }

    .sidebar-logo {
      max-width: 120px;
      height: auto;
      border-radius: 0.5rem;
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
    }

    .sidebar-logo:hover {
      transform: scale(1.05);
    }

    .nav-items {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .nav-items li {
      margin-bottom: 0.25rem;
    }

    .nav-items a {
      color: var(--text-secondary);
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 0.875rem 1.5rem;
      border-radius: 0.75rem;
      margin: 0 1rem;
      transition: var(--transition);
      font-weight: 500;
      position: relative;
    }

    .nav-items a i {
      margin-right: 0.75rem;
      width: 20px;
      text-align: center;
      font-size: 1.1rem;
      transition: var(--transition);
    }

    .nav-items a:hover {
      background-color: var(--sidebar-hover);
      color: var(--primary-color);
      transform: translateX(5px);
      box-shadow: var(--shadow-sm);
    }

    .nav-items a.active {
      background-color: var(--primary-color);
      color: white;
      box-shadow: var(--shadow-md);
    }

    .nav-items a.active i {
      color: white;
    }

    .logout-section {
      margin-top: auto;
      padding: 2rem 1.5rem 1.5rem;
      border-top: 1px solid var(--border-color);
    }

    .logout-btn {
      width: 100%;
      padding: 0.875rem;
      border-radius: 0.75rem;
      background-color: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
      transition: var(--transition);
      font-weight: 500;
    }

    .logout-btn:hover {
      background-color: var(--sidebar-hover);
      color: var(--text-primary);
      border-color: var(--primary-color);
      transform: translateY(-1px);
      box-shadow: var(--shadow-sm);
    }

    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar.collapsed .sidebar-header h4,
    .sidebar.collapsed .nav-items a span,
    .sidebar.collapsed .logout-btn span {
      display: none;
    }

    .sidebar.collapsed .nav-items a {
      justify-content: center;
      margin: 0 0.5rem;
      padding: 0.875rem 0.75rem;
    }

    /* Scrollbar sidebar */
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background-color: var(--border-color);
      border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background-color: var(--text-secondary);
    }

    /* Topbar */
    .topbar {
      background-color: var(--topbar-bg);
      position: fixed;
      top: 0;
      left: 260px;
      width: calc(100% - 260px);
      height: 70px;
      display: flex;
      align-items: center;
      padding: 0 2rem;
      z-index: 999;
      transition: var(--transition);
      border-bottom: 1px solid var(--border-color);
      box-shadow: var(--shadow-md);
    }

    .topbar .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--primary-color);
      text-decoration: none;
    }

    .topbar #dateTimeDisplay {
      font-weight: 500;
      color: var(--text-secondary);
      font-size: 0.95rem;
      white-space: nowrap;
    }

    /* Toggle Sidebar Button */
    .sidebar-toggle {
      background: none;
      border: none;
      color: var(--text-secondary);
      font-size: 1.25rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 0.5rem;
      transition: var(--transition);
      margin-right: 1rem;
    }

    .sidebar-toggle:hover {
      background-color: var(--sidebar-hover);
      color: var(--primary-color);
      transform: rotate(180deg);
    }

    /* Dark Mode Switch */
    .switch {
      position: relative;
      display: inline-block;
      width: 56px;
      height: 30px;
      margin-left: 1rem;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: var(--border-color);
      transition: var(--transition);
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "\f185"; /* Sun icon */
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      height: 26px;
      width: 26px;
      left: 2px;
      bottom: 2px;
      background-color: white;
      color: var(--primary-color);
      text-align: center;
      line-height: 26px;
      border-radius: 50%;
      transition: var(--transition);
      box-shadow: var(--shadow-sm);
    }

    input:checked + .slider {
      background-color: var(--primary-color);
    }

    input:checked + .slider:before {
      transform: translateX(26px);
      content: "\f186"; /* Moon icon */
      color: white;
      background-color: var(--secondary-color);
    }

    /* Content */
    .content {
      margin-left: 260px;
      padding: 90px 2rem 2rem;
      transition: var(--transition);
      background-color: var(--content-bg);
      min-height: calc(100vh - 70px);
    }

    .content.no-sidebar {
      margin-left: 0 !important;
      padding: 90px 2rem 2rem !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: var(--transition);
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .topbar {
        left: 0;
        width: 100%;
      }

      .content {
        margin-left: 0;
        padding: 90px 1rem 1rem;
      }

      .topbar {
        padding: 0 1rem;
      }

      .sidebar.collapsed {
        width: 260px; /* Override untuk mobile */
      }
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .content > * {
      animation: fadeIn 0.5s ease-out;
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body data-theme="light">

  {{-- Sidebar --}}
  @if (empty($hideSidebar))
    <div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <img src="{{ asset('images/logo.png') }}" alt="Logo Kawai Musik" class="sidebar-logo img-fluid">
    <h4>Kawai Musik</h4>
  </div>

  <ul class="nav-items">
   {{-- Cek apakah user adalah admin --}}
  @if(Auth::check() && Auth::user()->role === 'admin')
      <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i><span> Dashboard</span>
      </a>

      <a href="/admin/generate-qr" class="{{ request()->is('admin/generate-qr') ? 'active' : '' }}">
        <i class="fas fa-qrcode"></i><span> Generate QR</span>
      </a>

      <a href="/admin/tambah-siswa" class="{{ request()->is('admin/tambah-siswa') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i><span> Tambah Siswa</span>
      </a>

      <a href="/admin/tambah-pengajar" class="{{ request()->is('admin/tambah-pengajar') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i><span> Tambah Pengajar</span>
      </a>
 
      <a href="/admin/kelola_jadwal" class="{{ request()->is('admin/kelola_jadwal') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i><span> Kelola Jadwal</span>
      </a>
  
      <a href="/admin/reschedule" class="{{ request()->is('admin/reschedule') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i><span> Reschedule</span>
      </a>
  
      <a href="/admin/tambah-alatmusik" class="{{ request()->is('admin/tambah-alatmusik') ? 'active' : '' }}">
        <i class="fas fa-music"></i><span> Tambah Alat Musik</span>
      </a>
  
      <a href="/admin/laporan" class="{{ request()->is('admin/laporan') ? 'active' : '' }}">
        <i class="fas fa-file-invoice"></i><span> Laporan Absensi</span>
      </a>
        @endif

        {{-- Cek jika user adalah pengajar --}}
  @if(Auth::check() && Auth::user()->role === 'pengajar')
      <a href="{{ route('pengajar.dashboard') }}">
        <i class="fas fa-tachometer-alt"></i><span> Dashboard</span>
    </a>
    <a href="{{ route('pengajar.materi') }}">
        <i class="fas fa-book"></i><span> Materi</span>
    </a>
    <a href="{{ route('pengajar.scan-qr-form') }}">
        <i class="fas fa-qrcode"></i><span> Scan QR</span>
    </a>
    <a href="{{ route('pengajar.presensi') }}">
        <i class="fas fa-calendar-check"></i><span> Presensi</span>
    </a>
    <a href="{{ route('pengajar.laporan') }}">
        <i class="fas fa-file-invoice"></i><span> Laporan</span>
        </a>
    @endif
  </ul>

  <div class="logout-section">
    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn d-flex align-items-center justify-content-start" id="logoutBtn">
        <i class="fas fa-right-from-bracket me-2"></i>
        <span>Logout</span>
      </button>
    </form>
  </div>
</div>
  @endif

  

  {{-- Topbar --}}
  @if (empty($hideSidebar))
    <nav class="topbar">
      <button class="sidebar-toggle d-lg-none me-2" id="mobileSidebarToggle">
        <i class="fas fa-bars"></i>
      </button>
      <a class="navbar-brand" href="/admin/dashboard">Kawai Musik</a>

      <div class="ms-auto d-flex align-items-center">
        <div id="dateTimeDisplay" class="me-3"></div>

        <label class="switch">
          <input type="checkbox" id="darkModeSwitch">
          <span class="slider"></span>
        </label>
      </div>
    </nav>
  @endif

  {{-- Content --}}
  <div class="content {{ !empty($hideSidebar) ? 'no-sidebar' : '' }}" id="mainContent">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const topbar = document.querySelector('.topbar');
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const body = document.body;

    let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    // Initialize states
    window.addEventListener('DOMContentLoaded', () => {
      if (sidebar && sidebarCollapsed) {
        toggleSidebar();
      }

      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        body.setAttribute('data-theme', 'dark');
        if (darkModeSwitch) darkModeSwitch.checked = true;
      }
    });

    // Toggle Sidebar (Desktop)
    function toggleSidebar() {
      sidebarCollapsed = !sidebarCollapsed;
      sidebar.classList.toggle('collapsed', sidebarCollapsed);
      localStorage.setItem('sidebarCollapsed', sidebarCollapsed);

      const sidebarWidth = sidebarCollapsed ? 80 : 260;
      if (content) content.style.marginLeft = sidebarCollapsed ? `${sidebarWidth}px` : '';
      if (topbar) {
        topbar.style.left = `${sidebarWidth}px`;
        topbar.style.width = `calc(100% - ${sidebarWidth}px)`;
      }
    }

    // Mobile Sidebar Toggle
    if (mobileSidebarToggle) {
      mobileSidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
      });
    }

    // Dark Mode Toggle
    if (darkModeSwitch) {
      darkModeSwitch.addEventListener('change', () => {
        const isDark = darkModeSwitch.checked;
        body.setAttribute('data-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
      });
    }

    // Logout Confirmation
    if (logoutBtn) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin logout?')) {
          logoutForm.submit();
        }
      });
    }

    // Date & Time Update
    function updateDateTime() {
      const dateTimeDisplay = document.getElementById('dateTimeDisplay');
      if (!dateTimeDisplay) return;

      const now = new Date();
      const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now.getDay()];
      const tanggal = now.getDate().toString().padStart(2, '0');
      const bulan = (now.getMonth() + 1).toString().padStart(2, '0');
      const tahun = now.getFullYear();
      const jam = now.getHours().toString().padStart(2, '0');
      const menit = now.getMinutes().toString().padStart(2, '0');
      const detik = now.getSeconds().toString().padStart(2, '0');

      dateTimeDisplay.textContent = `${hari}, ${tanggal}/${bulan}/${tahun} ${jam}:${menit}:${detik}`;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Close mobile sidebar on outside click
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 768 && sidebar && !sidebar.contains(e.target) && !topbar.contains(e.target)) {
        sidebar.classList.remove('show');
      }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @yield('scripts')

  <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(session('success'))
        Swal.fire({
            title: '✅ Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: '❌ Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            title: '⚠️ Validasi Gagal',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            icon: 'warning',
            confirmButtonText: 'Cek Kembali'
        });
    @endif
});
</script>

@yield('scripts') <!-- biar masih bisa nambah script tambahan di halaman lain -->

</body>
</html>