<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
if ($role !== 'admin') {
    header('Location: unauthorized.php');
    exit();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Admin</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Pembungkus Halaman -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard_admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Dashboard Admin</div>
            </a>

            <hr class="sidebar-divider">

            <!-- Item Menu - Halaman Collapse -->
            <li class="nav-item">
                <a class="nav-link" href="data_dosen.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Lihat Data Dosen</span></a>
            </li>

            <!-- Item Menu - Tabel -->
            <li class="nav-item">
                <a class="nav-link" href="input_dosen.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Input Data Dosen</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="input_jadwal.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tambah Jadwal</span></a>
            </li>
            <!-- Item Menu - Tabel -->
            <li class="nav-item">
                <a class="nav-link" href="lihat_jadwal.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Jadwal</span></a>
            </li>


            <!-- Pembatas -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Pengatur Sidebar (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Pembungkus Konten -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Konten Utama -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Pencarian Topbar -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Navbar Topbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Item Nav - Pemberitahuan -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Penghitung - Pemberitahuan -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Pemberitahuan -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">Pusat Pemberitahuan</h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">12 Desember 2019</div>
                                        <span class="font-weight-bold">Laporan bulanan baru siap diunduh!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">7 Desember 2019</div>
                                        $290.29 telah disetorkan ke akun Anda!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">2 Desember 2019</div>
                                        Peringatan: Akun Anda mendekati batas kuota.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Tampilkan Semua Pemberitahuan</a>
                            </div>
                        </li>

                        <!-- Item Nav - Pesan -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Penghitung - Pesan -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Pesan -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Pusat Pesan</h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Ada tugas baru yang harus dinilai!</div>
                                        <div class="small text-gray-500">Dari: Admin</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Tampilkan Semua Pesan</a>
                            </div>
                        </li>

                        <!-- Item Nav - Profil -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo htmlspecialchars($username); ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - Profil -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.html">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil
                                </a>
                                <a class="dropdown-item" href="settings.html">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Keluar
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- Akhir Topbar -->

                <!-- Awal Konten Halaman -->
                <div class="container-fluid">

                    <!-- Judul Halaman -->
                    <h1 class="h3 mb-4 text-gray-800">Selamat datang, <?php echo htmlspecialchars($username); ?>!</h1>

                    <!-- Tambahkan widget atau informasi penting di sini -->
                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- Akhir Konten Utama -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Universitas XYZ 2025</span>
                    </div>
                </div>
            </footer>
            <!-- Akhir Footer -->
        </div>
        <!-- End of Pembungkus Konten -->
    </div>
    <!-- End of Pembungkus Halaman -->

    <!-- Tombol Scroll ke Atas-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal Logout-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siap untuk keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Keluar" di bawah jika Anda siap mengakhiri sesi ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="index.php">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript inti -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Skrip Kustom untuk Template Ini-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
