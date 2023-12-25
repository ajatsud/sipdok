<?php

if (!isset($view)) {
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
				<div>
					<h1 class="logo">
						<span class="logo-sip">Sip</span><span class="logo-dok">dok</span>
						<img class="logo-img" src="/logo.png" alt="Sipdok">
					</h1>
					<p class="logo-des"><small>Sistem Informasi Praktik Dokter</small></p>
				</div>
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
				<div class="breadcrumb">
					<h2>Pasien List</h2>
					<p><a href="/pasien/form" class="button">Pasien Entri</a></p>
				</div>
				<?php if (count($errors) > 0) : ?>
					<?php foreach ($errors as $key => $error) : ?>
						<?php if (is_string($key)) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<p style="color: red;"><?= $error ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if (count($pasiens) > 0) : ?>
					<div class="table-container">
						<table class="u-full-width">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nama</th>
									<th>Jenkel</th>
									<th>Lahir</th>
									<th>Alamat</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pasiens as $pasien) : ?>
									<tr>
										<td><?= $pasien["id"] ?></td>
										<td><?= $pasien["nama"] ?></td>
										<td><?= jenkel_display_format($pasien["jenkel"]) ?></td>
										<td><?= tanggal_display_format($pasien["lahir"]) ?></td>
										<td><?= $pasien["alamat"] ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</body>

</html>