@auth
    @if(Auth::User()->level == '4')
        @php
        $pegawai= DB::table('pegawai')->where('id_user',Auth::User()->id)->first();
      @endphp
      @if($pegawai->status_aktif == "Tidak Aktif")
      <script>
          window.location.href = "/keluar";
      </script>
      @endif
  @endif
  <?php
  // Inisialisasi variabel default
  $jabatan = null;
  $users = DB::table('users')->find(Auth::User()->id);

  // Cek apakah user adalah pegawai
  $pegawai = DB::table('pegawai')->where('id_user', Auth::User()->id)->first();
  if ($pegawai) {
      $jabatan = DB::table('jabatan')->where('id', $pegawai->id_jabatan)->first();
  }

  // Cek apakah user adalah admin
  $admin = DB::table('admin')->where('id_user', Auth::User()->id)->first();
  if ($admin) {
      $jabatan = DB::table('jabatan')->where('id', $admin->id_jabatan)->first();
  }

  // Cek apakah user adalah petugas
  $petugas = DB::table('petugas')->where('id_user', Auth::User()->id)->first();
  if ($petugas) {
      $jabatan = DB::table('jabatan')->where('id', $petugas->id_jabatan)->first();
  }

   // Cek apakah user adalah petugas
   $petugaslaundry = DB::table('petugaslaundry')->where('id_user', Auth::User()->id)->first();
  if ($petugaslaundry) {
      $jabatan = DB::table('jabatan')->where('id', $petugaslaundry->id_jabatan)->first();
  }

   // Cek apakah user adalah petugas
   $admin_perawat = DB::table('admin_perawat')->where('id_user', Auth::User()->id)->first();
  if ($admin_perawat) {
      $jabatan = DB::table('jabatan')->where('id', $admin_perawat->id_jabatan)->first();
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Admin, Dashboard, Bootstrap" />
    <link rel="shortcut icon" sizes="196x196" href="/favicon.ico">

    <title>
        {{ Auth::user()->name }} | Sistem Informasi Pengaduan
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css">
    <!-- build:css {{url('assets-admin')}}/assets/css/app.min.css -->
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/animate.css/animate.min.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/assets/css/core.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/assets/css/app.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet">
    <!-- endbuild -->

    <!-- Link Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <script src="{{url('assets-admin')}}/libs/bower/breakpoints.js/dist/breakpoints.min.js"></script>
    <script>
        Breakpoints();
    </script>
    <style>
      /* Hide spinner in WebKit browsers (Chrome, Safari, etc.) */
      input[type=number]::-webkit-outer-spin-button,
      input[type=number]::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
      }

      /* Hide spinner in Firefox */
      input[type=number] {
          -moz-appearance: textfield;
      }

      /* Prevent scrolling up/down with mouse wheel */
      input[type=number] {
          /* Ensure overflow is hidden */
          overflow: hidden;
      }
    </style>
    <style>
        /* Optional custom styles */
        .select2-container--default .select2-selection--single {
            height: 38px;
        }
    </style>
    <style>
      .align-middle-custom {
          vertical-align: middle !important;
      }
      #notification-container {
    position: fixed;
    top: 0;
    right: 0;
    padding: 20px;
    z-index: 1050;
    max-width: 300px;
}

.alert {
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

.alert-dismissible .btn-close {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 1050;
}

.alert-dismissible .btn-close:hover {
    background-color: transparent;
    border: none;
    color: #000;
}
.align-middle-custom {
    vertical-align: middle !important;
}
.gap-1 > * {
    margin: 0.2rem;
}


    </style>
       <style>
      /* Wrapper untuk semua notifikasi */
        .notif-wrapper {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            z-index: 9999;
            flex-wrap: wrap;
            justify-content: center;
        }

        .notif-container {
            max-width: 300px;
            background: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease;
        }

        .notif-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .notif-content {
            font-size: 14px;
            margin-bottom: 12px;
        }

        .notif-button {
            padding: 6px 12px;
            background: #ff5e5e;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    const userLevel = @json(Auth::user()->level);
</script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
             if (userLevel != 6) return;
            const audio = new Audio('/sound/notifikasi.mp3');
            audio.loop = true;

            const wrapper = document.getElementById('notifWrapper');

            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('panic-logs')
                    .listen('PanicLogCreated', (e) => {
                        const pasien = e.pasien ?? null;
                        const kamar = e.kamar ?? {};
                        const ruangan = e.ruangan ?? {};
                        const kamarId = kamar.id ?? null;

                        // Cek apakah notifikasi untuk kamar ini sudah ada
                        const existingNotif = wrapper.querySelector(`.notif-container[data-kamar-id="${kamarId}"]`);
                        if (existingNotif) {
                            console.log("Notifikasi untuk kamar ini sudah ada, abaikan.");
                            return;
                        }

                        const pasienNama = pasien?.nama ?? 'Tidak ada pasien';
                        const pasienKendala = pasien?.kendala ?? '-';
                        const ruanganNama = ruangan.nama ?? 'Tidak diketahui';
                        const kamarNomor = kamar.nomor_kamar ?? 'Tidak diketahui';

                        const notif = document.createElement('div');
                        notif.classList.add('notif-container');
                        notif.setAttribute('data-kamar-id', kamarId);

                        notif.innerHTML = `
                            <div class="notif-title">🚨 Panic Button Ditekan!</div>
                            <div class="notif-content">
                                <strong>Ruangan:</strong> ${ruanganNama}<br>
                                <strong>Nomor Kamar:</strong> ${kamarNomor}<br>
                                <strong>Waktu:</strong> ${e.created_at}<br>
                                <strong>Pasien:</strong> ${pasienNama}<br>
                                <strong>Kendala:</strong> ${pasienKendala}
                            </div>
                            <button class="notif-button">Matikan Alarm</button>
                        `;

                        wrapper.appendChild(notif);

                        // Mainkan audio jika belum menyala
                        if (audio.paused) {
                            audio.play().catch(err => console.error("Gagal memutar audio:", err));
                        }

                        // Tombol matikan alarm
                        const stopBtn = notif.querySelector('.notif-button');
                        stopBtn.addEventListener('click', () => {
                            notif.remove();

                            // Hentikan audio jika tidak ada notifikasi tersisa
                            if (wrapper.querySelectorAll('.notif-container').length === 0) {
                                audio.pause();
                                audio.currentTime = 0;
                            }
                        });
                    });
            } else {
                console.error("Laravel Echo belum terinisialisasi!");
            }
        });
</script>
@stack('scripts')
@vite(['resources/js/app.js'])
@stack('styles')
<!-- jQuery (wajib) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="menubar-left menubar-unfold menubar-light theme-primary">
    @if (Auth::user()->level == 6)
  <div class="notif-wrapper" id="notifWrapper"></div>
    @endif
<!--============= start main area -->

<!-- APP NAVBAR ==========-->
<!-- APP NAVBAR ==========-->
<nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">
     <!-- navbar header -->
  <div class="navbar-header">
    <button type="button" id="menubar-toggle-btn" class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger">
      <span class="sr-only">Toggle navigation</span>
      <span class="hamburger-box"><span class="hamburger-inner"></span></span>
    </button>

    <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="zmdi zmdi-hc-lg zmdi-more"></span>
    </button>

    <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="zmdi zmdi-hc-lg zmdi-search"></span>
    </button>
    <a href="/admin/home" class="navbar-brand">
      <span class="brand-icon"><i class="fa fa-gg"></i></span>
      <span class="brand-name">SI PASAN</span>
    </a>
  </div><!-- .navbar-header -->

  <div class="navbar-container container-fluid">
    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
        <li class="hidden-float hidden-menubar-top">
          <a href="javascript:void(0)" role="button" id="menubar-fold-btn" class="hamburger hamburger--arrowalt is-active js-hamburger">
            <span class="hamburger-box"><span class="hamburger-inner"></span></span>
          </a>
        </li>
        <li>
          <h5 class="page-title hidden-menubar-top hidden-float">Sistem Informasi Pengaduan RSUD</h5>
        </li>
      </ul>

      <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="zmdi zmdi-hc-lg zmdi-notifications"></i>
            </a>
        <div id="notification-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>
        <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-settings"></i></a>
          <ul class="dropdown-menu animated flipInY">
            <li><a href="/profil/{{ Auth::user()->id }}"><i class="zmdi m-r-md zmdi-hc-lg zmdi-account-box"></i>My Profile</a></li>
            <li><a href="/logout"><i class="zmdi m-r-md zmdi-hc-lg zmdi-sign-in"></i>logout</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0)" class="side-panel-toggle" data-toggle="class" data-target="#side-panel" data-class="open" role="button"><i class="zmdi zmdi-hc-lg zmdi-apps"></i></a>
        </li>
      </ul>
    </div>
  </div><!-- navbar-container -->
</nav>
<!--========== END app navbar -->

<!-- APP ASIDE ==========-->
<aside id="menubar" class="menubar light">
  <div class="app-user">
    <div class="media">
      <div class="media-left">
        <div class="avatar avatar-md avatar-circle">
          @if($pegawai != "")
          <img class="img-responsive"
          src="{{ $pegawai && $pegawai->foto ? url('public/profil/' . $pegawai->foto) : url('assets-admin/assets/images/user.png') }}"
          alt="avatar"/>
          @else
          <img class="img-responsive" src="{{url('assets-admin')}}/assets/images/user.png" alt="avatar"/>
          @endif
        </div><!-- .avatar -->
      </div>
      <div class="media-body">
        <div class="foldable">
          <h5>{{Auth::User()->name}}</h5>
          <ul>
            <li class="dropdown">
                <small>{{$jabatan ? $jabatan->nama:'Super Admin'}}</small>
            </li>
          </ul>
        </div>
      </div><!-- .media-body -->
    </div><!-- .media -->
  </div><!-- .app-user -->

  <div class="menubar-scroll">
    <div class="menubar-scroll-inner">
      <ul class="app-menu">
        <li class="@if ($activePage == 'dashboard') active @endif">
          <a href="/admin/home">
            <i class="menu-icon zmdi zmdi-view-dashboard zmdi-hc-lg"></i>
            <span class="menu-text">Dashboards</span>
          </a>
        </li>
        @if(Auth::User()->level == '1')
        <li class="has-submenu @if ($activePage == 'jabatan' || $activePage == 'ruangan' || $activePage == 'sarana' || $activePage == 'pasien') active @endif">
            <a href="javascript:void(0)" class="submenu-toggle">
              <i class="menu-icon zmdi zmdi-layers zmdi-hc-lg"></i>
              <span class="menu-text">Data Master</span>
              <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
            </a>
            <ul class="submenu">
              <li><a href="/admin/jabatan"><span class="menu-text @if ($activePage == 'jabatan') text-primary @endif">Jabatan</span></a></li>
              <li><a href="/admin/ruangan"><span class="menu-text @if ($activePage == 'ruangan') text-primary @endif">Ruangan</span></a></li>
              <li><a href="/admin/kamar"><span class="menu-text @if ($activePage == 'kamar') text-primary @endif">Kamar</span></a></li>
              <li><a href="/admin/sarana"><span class="menu-text @if ($activePage == 'sarana') text-primary @endif">Sarana Prasarana</span></a></li>
              <li><a href="/admin/pasien"><span class="menu-text @if ($activePage == 'pasien') text-primary @endif">Pasien</span></a></li>
            </ul>
          </li>

          <li class="has-submenu @if ($activePage == 'admin' || $activePage == 'adminperawat' || $activePage == 'petugas' || $activePage == 'petugaslaundry'|| $activePage == 'pegawai'|| $activePage == 'panic') active @endif">
            <a href="javascript:void(0)" class="submenu-toggle">
                <i class="menu-icon fas fa-users"></i>
                <span class="menu-text">Data User</span>
                <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
            </a>
            <ul class="submenu">
                <!-- Admin -->
                <li class="@if ($activePage == 'Admin') active @endif">
                    <a href="/admin/admin" class="menu-item">
                        <i class="bi bi-person-badge-fill"></i> <!-- Admin Icon -->
                        <span class="menu-text">Admin</span>
                    </a>
                </li>
               <!-- Admin Perawat -->
                <li class="@if ($activePage == 'AdminPerawat') active @endif">
                    <a href="/admin/perawat" class="menu-item">
                        <i class="bi bi-person-check-fill"></i> <!-- Icon perawat/staff -->
                        <span class="menu-text">Admin Perawat</span>
                    </a>
                </li>


                <!-- Petugas -->
                <li class="@if ($activePage == 'petugas') active @endif">
                    <a href="/admin/petugas" class="menu-item">
                        <i class="fas fa-user-cog"></i> <!-- Petugas Icon -->
                        <span class="menu-text">Petugas</span>
                    </a>
                </li>

                 <!-- Petugas -->
                 <li class="@if ($activePage == 'petugaslaundry') active @endif">
                    <a href="/admin/petugaslaundry" class="menu-item">
                        <i class="fas fa-user-cog"></i> <!-- Petugas Icon -->
                        <span class="menu-text">Petugas Laundry</span>
                    </a>
                </li>

                <!-- Pegawai -->
                <li class="@if ($activePage == 'pegawai') active @endif">
                    <a href="/admin/pegawai" class="menu-item">
                        <i class="fas fa-users"></i> <!-- Pegawai Icon -->
                        <span class="menu-text">Pegawai</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Pengaduan -->
        <li class="@if ($activePage == 'pengaduan') active @endif">
            <a href="/admin/pengaduan" class="menu-item">
                <i class="menu-icon zmdi zmdi-file-text zmdi-hc-lg"></i> <!-- Pengaduan Icon -->
                <span class="menu-text">Pengaduan</span>

            </a>
        </li>
         <!-- Permintaan Laundry -->
         <li class="@if ($activePage == 'permintaan_laundry') active @endif">
            <a href="/admin/laundry">
                <i class="menu-icon zmdi zmdi-washing-machine zmdi-hc-lg"></i> <!-- Laundry Icon -->
                <span class="menu-text">Permintaan Laundry</span>
            </a>
        </li>

        @elseif(Auth::User()->level == '2')
         <!-- Admin Perawat -->
        <li class="@if ($activePage == 'AdminPerawat') active @endif">
            <a href="/admin/perawat" class="menu-item">
            <i class="menu-icon bi bi-person-check-fill"></i> <!-- Icon perawat/staff -->
                <span class="menu-text">Admin Perawat</span>
            </a>
        </li>

        <!-- Petugas -->
        <li class="@if ($activePage == 'petugas') active @endif">
            <a href="/admin/petugas" class="menu-item">
                <i class=" menu-icon fas fa-user-cog"></i> <!-- Petugas Icon -->
                <span class="menu-text">Petugas</span>
            </a>
        </li>

        <!-- Petugas -->
        <li class="@if ($activePage == 'petugaslaundry') active @endif">
            <a href="/admin/petugaslaundry">
                <i class="menu-icon fas fa-user-cog"></i> <!-- Petugas Icon -->
                <span class="menu-text">Petugas Laundry</span>
            </a>
        </li>

        <!-- Pengaduan -->
        <li class="@if ($activePage == 'pengaduan') active @endif">
            <a href="/admin/pengaduan" class="menu-item">
                <i class="menu-icon zmdi zmdi-file-text zmdi-hc-lg"></i> <!-- Pengaduan Icon -->
                <span class="menu-text">Pengaduan</span>
            </a>
        </li>
        <!-- Permintaan Laundry -->
        <li class="@if ($activePage == 'permintaan_laundry') active @endif">
            <a href="/admin/laundry">
                <i class="menu-icon zmdi zmdi-washing-machine zmdi-hc-lg"></i> <!-- Laundry Icon -->
                <span class="menu-text">Permintaan Laundry</span>
            </a>
        </li>



        @elseif(Auth::User()->level == '3')
        <li class="@if ($activePage == 'pengaduan') active @endif">
            <a href="/petugas/pengaduan">
              <i class="menu-icon zmdi zmdi-file-text zmdi-hc-lg"></i>
              <span class="menu-text">Pengaduan</span>
            </a>
          </li>

        @elseif(Auth::User()->level == '4')
        <li class="@if ($activePage == 'pengaduan') active @endif">
            <a href="/pegawai/pengaduan">
              <i class="menu-icon zmdi zmdi-file-text zmdi-hc-lg"></i>
              <span class="menu-text">Pengaduan</span>
            </a>
          </li>

           <!-- Permintaan Laundry -->
            <li class="@if ($activePage == 'permintaan_laundry') active @endif">
                <a href="/pegawai/laundry">
                    <i class="menu-icon zmdi zmdi-washing-machine zmdi-hc-lg"></i> <!-- Laundry Icon -->
                    <span class="menu-text">Permintaan Laundry</span>
                </a>
            </li>
        @elseif(Auth::User()->level == '5')
           <!-- Permintaan Laundry -->
            <li class="@if ($activePage == 'permintaan_laundry') active @endif">
                <a href="/petugaslaundry/laundry" class="menu-item">
                    <i class="menu-icon zmdi zmdi-washing-machine zmdi-hc-lg"></i> <!-- Laundry Icon -->
                    <span class="menu-text">Permintaan Laundry</span>
                </a>
            </li>

        @elseif(Auth::User()->level == '6')
          <li class="@if ($activePage == 'pasien') active @endif">
              <a href="/admin/pasien">
                  <i class="menu-icon zmdi zmdi-accounts zmdi-hc-lg"></i>
                  <span class="menu-text @if ($activePage == 'pasien') text-primary @endif">Pasien</span>
              </a>
          </li>
        <li class="@if ($activePage == 'panic') active @endif">
            <a href="/admin/pasien/panic-button">
                <i class="menu-icon zmdi zmdi-alert-circle zmdi-hc-lg"></i>
                <span class="menu-text @if ($activePage == 'panic') text-primary @endif">Panggilan Panic Button</span>
            </a>
        </li>
      @endif
      </ul><!-- .app-menu -->
    </div><!-- .menubar-scroll-inner -->
  </div><!-- .menubar-scroll -->
</aside>
<!--========== END app aside -->

<!-- navbar search -->
<div id="navbar-search" class="navbar-search collapse">
  <div class="navbar-search-inner">
    <form action="#">
      <span class="search-icon"><i class="fa fa-search"></i></span>
      <input class="search-field" type="search" placeholder="search..."/>
    </form>
    <button type="button" class="search-close" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false">
      <i class="fa fa-close"></i>
    </button>
  </div>
  <div class="navbar-search-backdrop" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"></div>
</div><!-- .navbar-search -->

<!-- APP MAIN ==========-->
<main id="app-main" class="app-main">
  <div class="wrap">
    <section class="app-content">
        @yield('content')
    </section><!-- #dash-content -->
  </div><!-- .wrap -->
  <!-- APP FOOTER -->
  <div class="wrap p-t-0">
    <footer class="app-footer">
      <div class="clearfix">
        <div class="copyright pull-left">Copyright &copy; {{date('Y')}}</div>
      </div>
    </footer>
  </div>
  <!-- /#app-footer -->
</main>
<!--========== END app main -->

    <!-- APP CUSTOMIZER -->
    <div id="app-customizer" class="app-customizer">
        <a href="javascript:void(0)"
            class="app-customizer-toggle theme-color"
            data-toggle="class"
            data-class="open"
            data-active="false"
            data-target="#app-customizer">
            <i class="fa fa-gear"></i>
        </a>
        <div class="customizer-tabs">
            <!-- tabs list -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#menubar-customizer" aria-controls="menubar-customizer" role="tab" data-toggle="tab">Menubar</a></li>
                <li role="presentation"><a href="#navbar-customizer" aria-controls="navbar-customizer" role="tab" data-toggle="tab">Navbar</a></li>
            </ul><!-- .nav-tabs -->

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane in active fade" id="menubar-customizer">
                    <div class="hidden-menubar-top hidden-float">
                        <div class="m-b-0">
                            <label for="menubar-fold-switch">Fold Menubar</label>
                            <div class="pull-right">
                                <input id="menubar-fold-switch" type="checkbox" data-switchery data-size="small" />
                            </div>
                        </div>
                        <hr class="m-h-md">
                    </div>
                    <div class="radio radio-default m-b-md">
                        <input type="radio" id="menubar-light-theme" name="menubar-theme" data-toggle="menubar-theme" data-theme="light">
                        <label for="menubar-light-theme">Light</label>
                    </div>

                    <div class="radio radio-inverse m-b-md">
                        <input type="radio" id="menubar-dark-theme" name="menubar-theme" data-toggle="menubar-theme" data-theme="dark">
                        <label for="menubar-dark-theme">Dark</label>
                    </div>
                </div><!-- .tab-pane -->
                <div role="tabpanel" class="tab-pane fade" id="navbar-customizer">
                    <!-- This Section is populated Automatically By javascript -->
                </div><!-- .tab-pane -->
            </div>
        </div><!-- .customizer-taps -->
        <hr class="m-0">
        <div class="customizer-reset">
            <button id="customizer-reset-btn" class="btn btn-block btn-outline btn-primary">Reset</button>
            <a href="#" class="m-t-sm btn btn-block btn-success"><i class="fa fa-book"></i> Manual Book</a>
        </div>
    </div><!-- #app-customizer -->

    <!-- SIDE PANEL -->
    <div id="side-panel" class="side-panel">
        <div class="panel-header">
            <h4 class="panel-title">Petugas</h4>
        </div>
        <div class="scrollable-container">

    <!-- build:js {{url('assets-admin')}}/assets/js/core.min.js -->
    <script src="{{url('assets-admin')}}/libs/bower/jquery/dist/jquery.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/jquery-ui/jquery-ui.min.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/jQuery-Storage-API/jquery.storageapi.min.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/bootstrap-sass/assets/javascripts/bootstrap.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/PACE/pace.min.js"></script>
    <!-- endbuild -->

    <!-- build:js {{url('assets-admin')}}/assets/js/app.min.js -->
    <script src="{{url('assets-admin')}}/assets/js/library.js"></script>
    <script src="{{url('assets-admin')}}/assets/js/plugins.js"></script>
    <script src="{{url('assets-admin')}}/assets/js/app.js"></script>
    <!-- endbuild -->
    <script src="{{url('assets-admin')}}/libs/bower/moment/moment.js"></script>
    <script src="{{url('assets-admin')}}/libs/bower/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="{{url('assets-admin')}}/assets/js/fullcalendar.js"></script>
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#default-datatable').DataTable();
      });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%', // Adjust the width as needed
                allowClear: false // Optionally allow clearing the selection
            });
        });
    </script>
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 3000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     @stack('scripts')
</body>
</html>
@endauth
@guest
  <script>window.location = "/login";</script>
@endguest
