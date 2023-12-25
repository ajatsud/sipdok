<?php

if (!isset($view))
{
	exit("No direct script access allowed");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link href="/style.css?version=<?= filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'style.css') ?>" rel="stylesheet">
</head>

<body>

	<div class="container">
		<div class="row">
			<div class="twelve columns header">
				<h1 class="logo">
					<span class="logo-sip">Sip</span><span class="logo-dok">dok</span>
					<img class="logo-img" src="/logo.png" alt="Sipdok">
				</h1>
				<div class="nav">
					<a href="/" <?= ($menu == "dashboard") ? 'class="active"' : ""  ?>>Dashboard</a>
					<a href="/pasien" <?= ($menu == "pasien") ? 'class="active"' : ""  ?>>Pasien</a>
					<a href="/pendaftaran" <?= ($menu == "pendaftaran") ? 'class="active"' : ""  ?>>Pendaftaran</a>
					<a href="/antrian" <?= ($menu == "antrian") ? 'class="active"' : ""  ?>>Antrian</a>
					<a href="/pemeriksaan" <?= ($menu == "pemeriksaan") ? 'class="active"' : ""  ?>>Pemeriksaan</a>
					<a href="/kasir" <?= ($menu == "kasir") ? 'class="active"' : ""  ?>>Kasir</a>
					<a href="/laporan" <?= ($menu == "laporan") ? 'class="active"' : ""  ?>>Laporan</a>
					<a href="/user/logout" <?= ($menu == "user") ? 'class="active"' : ""  ?>>Logout</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<h2>Pasien Entri</h2>
			</div>
		</div>
		<form method="post" action="/pasien/save">
			<div class="row">
				<div class="six columns">
					<label>ID</label>
					<input type="text" name="id" placeholder="Otomatis" class="u-full-width" readonly>
				</div>
				<div class="six columns">
					<label>Nama</label>
					<input type="text" name="nama" placeholder="Nama" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label>Jenkel</label>
					<div style="display: flex; flex-direction: row; gap: 20px; justify-content: flex-start;">
						<div style="display: flex; flex-direction: row;">
							<div>Laki-Laki</div>
							<div><input type="radio" name="jenkel" value="l" class="u-full-width"></div>
						</div>
						<div style="display: flex; flex-direction: row;">
							<div>Perempuan</div>
							<div><input type="radio" name="jenkel" value="p" class="u-full-width"></div>
						</div>
					</div>
				</div>
				<div class="six columns">
					<label>Tanggal Lahir</label>
					<input type="date" name="lahir" value="<?= date('Y-m-d') ?>" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<label>Alamat</label>
					<textarea name="alamat" class="u-full-width"></textarea>
				</div>
			</div>
			<input type="submit">
		</form>
	</div>

</body>

</html>