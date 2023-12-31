<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link href="/res/style.css?version=<?= filemtime(APP_DIR . APP_SEP . ".." . APP_SEP . "res" . APP_SEP .  'style.css') ?>" rel="stylesheet">
</head>

<body>
	<div class="container">
		<?php if (isset($_SESSION["username"])) : ?>
			<div class="row">
				<div class="twelve columns header">
					<div>
						<h1 class="logo">
							<span class="logo-sip">Sip</span><span class="logo-dok">dok</span>
							<img class="logo-img" src="/res/logo.png" alt="Sipdok">
						</h1>
						<p class="logo-des"><small>Sistem Informasi Praktik Dokter</small></p>
					</div>
					<div class="nav">
						<a <?= (isset($menu) && $menu == "dashboard") ? 'class="active"' : "" ?> href="/">Dashboard</a>
						<a <?= (isset($menu) && $menu == "pasien") ? 'class="active"' : "" ?> href="/pasien">Pasien</a>
						<a <?= (isset($menu) && $menu == "pendaftaran") ? 'class="active"' : "" ?> href="/pendaftaran">Pendaftaran</a>
						<a <?= (isset($menu) && $menu == "antrian") ? 'class="active"' : "" ?> href="/antrian">Antrian</a>
						<a <?= (isset($menu) && $menu == "pemeriksaan") ? 'class="active"' : "" ?> href="/pemeriksaan">Pemeriksaan</a>
						<a <?= (isset($menu) && $menu == "kasir") ? 'class="active"' : "" ?> href="/kasir">Kasir</a>
						<a <?= (isset($menu) && $menu == "laporan") ? 'class="active"' : "" ?> href="/laporan">Laporan</a>
						<a <?= (isset($menu) && $menu == "logout") ? 'class="active"' : "" ?> href="/user/logout">Logout</a>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="row">
				<div class="six offset-by-three columns header">
					<div>
						<h1 class="logo">
							<span class="logo-sip">Sip</span><span class="logo-dok">dok</span>
							<img class="logo-img" src="/res/logo.png" alt="Sipdok">
						</h1>
						<p class="logo-des"><small>Sistem Informasi Praktik Dokter</small></p>
					</div>
				</div>
			</div>
		<?php endif; ?>