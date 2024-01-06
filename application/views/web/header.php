<?php
$ceks  = $this->session->userdata('pkl_smk@TA-D3-2023');
$level = $this->session->userdata('level@PKL-2023');

if ($level == 'admin') {
  $cek    = $this->db->get_where('tbl_user', "username='$ceks'")->row();
  $link_nilai = 'users/nilai_praktik';
} elseif ($level == 'pembimbing') {
  $cek    = $this->db->get_where('tbl_pembimbing', "username='$ceks'")->row();
  $link_nilai = 'users/nilai';
} else {
  $cek    = $this->db->get_where('tbl_siswa', "nis_siswa='$ceks'")->row();
  $link_nilai = 'users/nilai_prakerin';
}

$menu     = strtolower($this->uri->segment(1));
$sub_menu = strtolower($this->uri->segment(2));
$sub_menu3 = strtolower($this->uri->segment(3));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <base href="<?php echo base_url(); ?>" />
  <title><?php echo $judul_web; ?></title>

  <!-- Favicons -->
  <link href="public/frontend/assets/img/logo-new.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="public/frontend/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="public/frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="public/frontend/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="public/frontend/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="public/frontend/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="public/frontend/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Vendor JS Files -->
  <script src="public/frontend/assets/vendor/aos/aos.js"></script>
  <script src="public/frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="public/frontend/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="public/frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="public/frontend/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="public/frontend/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="public/frontend/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="public/frontend/assets/js/main.js"></script>


  <!-- Template Main CSS File -->
  <link href="public/frontend/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-primary">

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top bg-primary">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="<?php echo base_url(); ?>">PKL SMK AL-AMIIN</a></h1>
      <nav id="navbar" class="navbar">
        <ul>
          <li<?php if ($sub_menu == '') { echo ' class="nav-link scrollto active"'; } ?>><a href="<?php echo base_url(); ?>">Beranda</a></li>
            <li<?php if ($sub_menu == 'info') { echo ' class="nav-link scrollto active"'; } ?>><a href="web/info">Informasi</a></li>
              <li<?php if ($sub_menu == 'industri') { echo ' class="nav-link scrollto active"'; } ?>><a href="web/industri">Industri</a></li>
                <li<?php if ($sub_menu == 'login') { echo ' class="nav-link scrollto active"'; } ?>><a href="web/login">Login</a></li>
        </ul>
        <i class="bi mobile-nav-toggle bi-list"></i>
      </nav>

    </div>
  </header><!-- End Header -->


  <!-- Page container -->
  <div class="page-container">

    <!-- Page content -->
    <div class="page-content">

      <!-- Main content -->
      <div class="content-wrapper">

        <!-- Content area -->
        <div class="content">