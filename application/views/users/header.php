<?php
$cek    = $user->row();
$nama   = $cek->nama_lengkap;

$menu 		= strtolower($this->uri->segment(1));
$sub_menu = strtolower($this->uri->segment(2));
$sub_menu3 = strtolower($this->uri->segment(3));
?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<base href="<?php echo base_url(); ?>" />
	<title><?php echo $judul_web; ?></title>

	<link href="public/frontend/assets/img/logo-new.png" rel="icon">

	<!-- Global stylesheets -->
	<link href="public/backend/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="public/backend/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="public/backend/assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="public/backend/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="public/backend/assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="public/backend/assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="public/backend/assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="public/backend/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="public/backend/assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<script src="public/backend/assets/js/select2.min.js"></script>
	<script>
		$(document).ready(function() {
			$(".cari_label").select2({
				placeholder: "Pilih Label"
			});
			$(".cari_jurusan").select2({
				placeholder: "Pilih Jurusan"
			});
			$(".cari_kelas").select2({
				placeholder: "Pilih Kelas"
			});
			$(".cari_pemb").select2({
				placeholder: "Pilih Pembimbing"
			});
			$(".cari_siswa").select2({
				placeholder: "Pilih Siswa"
			});
			$(".cari_industri").select2({
				placeholder: "Pilih Industri"
			});
		});
	</script>

	<?php
	if ($sub_menu == "" or $sub_menu == "profile" or $sub_menu == "lap_sk" or $sub_menu == "lap_sm") { ?>
		<!-- Theme JS files -->

		<link rel="stylesheet" href="public/backend/assets/calender/css/style.css">
		<link rel="stylesheet" href="public/backend/assets/calender/css/pignose.calendar.css">

		<script type="text/javascript" src="public/backend/assets/js/plugins/visualization/d3/d3.min.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/forms/styling/switchery.min.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/forms/styling/uniform.min.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/ui/moment/moment.min.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/pickers/daterangepicker.js"></script>

		<script type="text/javascript" src="public/backend/assets/js/core/app.js"></script>
		<!-- <script type="text/javascript" src="assets/js/pages/dashboard.js"></script> -->
		<script src="public/backend/assets/calender/js/pignose.calendar.js"></script>
		<!-- /theme JS files -->
	<?php
	} ?>

	<?php
	if (
		$sub_menu == "jurusan" or $sub_menu == "kelas" or $sub_menu == "pembimbing" or $sub_menu == "siswa" or $sub_menu == "industri" or $sub_menu == "penempatan" or $sub_menu == "monitoring" or $sub_menu == "laporan" or $sub_menu == "sidang" or $sub_menu == "nilai_pkl" or
		$sub_menu == "d_siswa" or $sub_menu == "bimbingan" or $sub_menu == "nilai" or
		$sub_menu == "status_prakerin" or $sub_menu == "bimbingan_siswa" or $sub_menu == "nilai_prakerin"
	) { ?>
		<!-- Theme JS files -->
		<script type="text/javascript" src="public/backend/assets/js/plugins/tables/datatables/datatables.min.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/editors/summernote/summernote.min.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/plugins/forms/styling/uniform.min.js"></script>

		<script type="text/javascript" src="public/backend/assets/js/core/app.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/pages/editor_summernote.js"></script>
		<script type="text/javascript" src="public/backend/assets/js/pages/datatables_basic.js"></script>

		<!-- /theme JS files -->
	<?php
	} ?>


</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-default header">
		<div class="navbar-header">
			<div class="navbar-brand">
				<span class="text-center text-primary"><b>PKL SMK AL-AMIIN</b></span>
			</div>
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="foto/<?php if ($level == 'Siswa') {
											echo "siswa/";
										}
										if ($level != 'Pembimbing') {
											if ($cek->foto == '') {
												echo 'default.png';
											} else {
												echo $cek->foto;
											}
										} else {
											echo "default.png";
										} ?>" class="img-circle" alt="" width="30" height="28">
						<span><?php echo ucwords($nama); ?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="users/profile"><i class="icon-user"></i> Profil</a></li>
						<li class="divider"></li>
						<li><a href="web/logout"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">
			<!-- Main sidebar -->
			<div class="sidebar sidebar-main text-white bg-primary">
				<div class="sidebar-content">
					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="users/profile" class="media-left"><img src="foto/<?php if ($level == 'Siswa') {
																								echo "siswa/";
																							}
																							if ($level != 'Pembimbing') {
																								if ($cek->foto == '') {
																									echo 'default.png';
																								} else {
																									echo $cek->foto;
																								}
																							} else {
																								echo "default.png";
																							} ?>" class="img-circle img-sm" alt=""></a>
								<div class="media-body">
									<span class="media-heading text-semibold"><?php echo ucwords($nama); ?></span>
									<div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> &nbsp;<?php echo ucwords($level); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->
					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">
								<!-- Main -->
								<li class="navigation-header"><span>MASTER DATA</span> <i class="icon-menu" title="Main pages"></i></li>
								<!-- <li class="<?php if ($sub_menu == "") {
													echo 'active';
												} ?>"><a href=""><i class="icon-home4"></i> <span>Beranda</span></a></li> -->
								<?php if ($level == 'Siswa') { ?>
									<li class="<?php if ($sub_menu == "status_pkl") {
													echo 'active';
												} ?>"><a href="users/status_pkl"><i class="icon-cogs"></i> <span>Status PKL</span></a></li>
								<?php } ?>
								<li class="<?php if ($sub_menu == "profile") {
												echo 'active';
											} ?>"><a href="users/profile"><i class="icon-user"></i> <span>Profil</span></a></li>

								<?php if ($level == 'Admin') { ?>
									<!-- <li class="<?php if ($sub_menu == "info") {
														echo 'active';
													} ?> bg-info"><a href="users/info"><i class="icon-info22"></i> <span>Kelola Info</span></a></li> -->
									<li class="<?php if ($sub_menu == "jurusan") {
													echo 'active';
												} ?>"><a href="users/jurusan"><i class="icon-database-add"></i> <span>Jurusan</span></a></li>
									<li class="<?php if ($sub_menu == "kelas") {
													echo 'active';
												} ?>"><a href="users/kelas"><i class="icon-book"></i> <span>Kelas</span></a></li>
									<li class="<?php if ($sub_menu == "pembimbing") {
													echo 'active';
												} ?>"><a href="users/pembimbing"><i class="icon-users"></i> <span>Pembimbing</span></a></li>
									<li class="<?php if ($sub_menu == "siswa") {
													echo 'active';
												} ?>"><a href="users/siswa"><i class="icon-users"></i> <span>Siswa</span></a></li>
									<li class="<?php if ($sub_menu == "industri") {
													echo 'active';
												} ?>"><a href="users/industri"><i class="icon-office"></i> <span>Industri</span></a></li>
									<li class="<?php if ($sub_menu == "tempat") {
													echo 'active';
												} ?>"><a href="users/tempat"><i class="icon-link2"></i> <span>Tempat</span></a></li>
									<li class="<?php if ($sub_menu == "monitoring") {
													echo 'active';
												} ?>"><a href="users/monitoring"><i class="icon-stats-bars2"></i> <span>Log Book</span></a></li>
									<li class="<?php if ($sub_menu == "laporan") {
													echo 'active';
												} ?>"><a href="users/laporan"><i class="icon-archive"></i> <span>Laporan</span></a></li>
									<li class="<?php if ($sub_menu == "sidang") {
													echo 'active';
												} ?>"><a href="users/sidang"><i class="icon-file-text"></i> <span>Sidang</span></a></li>
									<li class="<?php if ($sub_menu == "nilai") {
													echo 'active';
												} ?>"><a href="users/nilai"><i class="icon-star-full2"></i> <span>Nilai</span></a></li>

								<?php } elseif ($level == 'Pembimbing') { ?>
									<li class="<?php if ($sub_menu == "d_siswa") {
													echo 'active';
												} ?>"><a href="users/d_siswa"><i class="icon-book3"></i> <span>Daftar Siswa</span></a></li>
									<li class="<?php if ($sub_menu == "bimbingan") {
													echo 'active';
												} ?>"><a href="users/bimbingan"><i class="icon-pencil7"></i> <span>Bimbingan</span></a></li>
									<li class="<?php if ($sub_menu == "monitoring_pemb") {
													echo 'active';
												} ?>"><a href="users/monitoring_pemb"><i class="icon-stats-bars2"></i> <span>Log Book</span></a></li>

								<?php } elseif ($level == 'Siswa') { ?>
									<li class="<?php if ($sub_menu == "monitoring_siswa") {
													echo 'active';
												} ?>"><a href="users/monitoring_siswa"><i class="icon-stats-bars2"></i> <span>Log Book</span></a></li>
									<li class="<?php if ($sub_menu == "bimbingan_siswa") {
													echo 'active';
												} ?>"><a href="users/bimbingan_siswa"><i class="icon-envelop5"></i> <span>Bimbingan</span></a></li>
									<li class="<?php if ($sub_menu == "laporan_siswa") {
													echo 'active';
												} ?>"><a href="users/laporan_siswa"><i class="icon-archive"></i> <span>Laporan</span></a></li>
									<li class="<?php if ($sub_menu == "sidang_siswa") {
													echo 'active';
												} ?>"><a href="users/sidang_siswa"><i class="icon-file-text"></i> <span>Sidang</span></a></li>
									<li class="<?php if ($sub_menu == "nilai_siswa") {
													echo 'active';
												} ?>"><a href="users/nilai_siswa"><i class="icon-star-full2"></i> <span>Nilai Siswa</span></a></li>
								<?php } ?>


								<!-- /main -->

								<!-- Logout -->
								<li class="navigation-header"><span>Keluar</span> <i class="icon-menu" title="Forms"></i></li>
								<li><a href="web/logout"><i class="icon-switch2"></i> <span>Keluar </span></a></li>

								<!-- /logout -->

							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
			<!-- /main sidebar -->

