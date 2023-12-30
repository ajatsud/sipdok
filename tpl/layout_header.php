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
	<link href="/res/style.css?version=<?= filemtime(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR .  'style.css') ?>" rel="stylesheet">
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
						<a class="button <?= ($menu == "dashboard") ? "button-success" : ""  ?>" href="/">Dashboard</a>
						<a class="button <?= ($menu == "pasien") ? "button-success" : ""  ?>" href="/pasien">Pasien</a>
						<a class="button <?= ($menu == "pendaftaran") ? "button-success" : ""  ?>" href="/pendaftaran">Pendaftaran</a>
						<a class="button <?= ($menu == "antrian") ? "button-success" : ""  ?>" href="/antrian">Antrian</a>
						<a class="button <?= ($menu == "pemeriksaan") ? "button-success" : ""  ?>" href="/pemeriksaan">Pemeriksaan</a>
						<a class="button <?= ($menu == "kasir") ? "button-success" : ""  ?>" href="/kasir">Kasir</a>
						<a class="button <?= ($menu == "laporan") ? "button-success" : ""  ?>" href="/laporan">Laporan</a>
						<a class="button <?= ($menu == "user") ? "button-success" : ""  ?>" href="/user/logout">Logout</a>
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