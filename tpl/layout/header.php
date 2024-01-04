<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<link href="/res/style.css?version=<?= filemtime(DIR . SEP . "res" . SEP .  'style.css') ?>" rel="stylesheet">
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
						<a <?= (isset($menu) && $menu == "rekmed") ? 'class="active"' : "" ?> href="/rekmed/form">Rekam Medis</a>
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