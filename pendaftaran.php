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
					<h2>Pendaftaran</h2>
					<p><a href="/pendaftaran" class="button">Pendaftaran</a></p>
				</div>
			</div>
		</div>
		<div class="box">
			<form method="post" action="/pasien/save">
				<div class="row">
					<div class="six-column">
						<?php if (count($errors) > 0) : ?>
							<?php foreach ($errors as $key => $error) : ?>
								<?php if (is_string($key)) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<p style="color: red;"><?= $error ?></p>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<div class="row">
							<div class="six columns">
								<label>ID</label>
								<input type="text" name="id" value="<?= $inputs["id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
							</div>
							<div class="six columns">
								<label>Pasien ID</label>
								<input type="text" name="pasien_id" id="pasien_id" value="<?= $inputs["pasien_id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
							</div>
						</div>
					</div>
					<div class="six columns">
						<label>Nama</label>
						<input type="text" name="nama" id="nama" value="<?= $inputs["nama"] ?? "" ?>" onkeyup="popup_pasien();" placeholder="Nama" class="u-full-width">
						<?php if (isset($errors["nama"])) : ?>
							<p style="color: red;"><?= $errors["nama"] ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="twelve columns">
						<div id="pasien-list"></div>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label>Jenkel</label>
						<div style="display: flex; flex-direction: row; gap: 20px; justify-content: flex-start;">
							<div style="display: flex; flex-direction: row;">
								<div>Laki-Laki</div>
								<div><input type="radio" name="jenkel" value="l" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "l") ? "checked" : "" ?> class="u-full-width"></div>
							</div>
							<div style="display: flex; flex-direction: row;">
								<div>Perempuan</div>
								<div><input type="radio" name="jenkel" value="p" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "p") ? "checked" : "" ?> class="u-full-width"></div>
							</div>
						</div>
						<?php if (isset($errors["jenkel"])) : ?>
							<p style="color: red;"><?= $errors["jenkel"] ?></p>
						<?php endif; ?>
					</div>
					<div class="six columns">
						<label>Tanggal Lahir</label>
						<input type="date" name="lahir" value="<?= (isset($inputs["lahir"])) ? date("Y-m-d", strtotime($inputs["lahir"])) : date("Y-m-d") ?>" class="u-full-width" readonly>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label>Alamat</label>
						<textarea name="alamat" class="u-full-width" readonly><?= $inputs["alamat"] ?? "" ?></textarea>
						<?php if (isset($errors["alamat"])) : ?>
							<p style="color: red;"><?= $errors["alamat"] ?></p>
						<?php endif; ?>
					</div>
					<div class="six columns">
						<label>Keluhan</label>
						<textarea name="keluhan" class="u-full-width"><?= $inputs["keluhan"] ?? "" ?></textarea>
						<?php if (isset($errors["keluhan"])) : ?>
							<p style="color: red;"><?= $errors["keluhan"] ?></p>
						<?php endif; ?>
					</div>
				</div>
				<input type="submit" class="button-primary">
			</form>
		</div>
	</div>
	<script>
		function popup_pasien() {
			let nama = document.getElementById("nama");

			if (nama.value.length >= 2) {
				document.getElementById("pasien-list").innerHTML = "Loading...";

				let ajax = new XMLHttpRequest();

				ajax.open("POST", "/popup/pasien/pendaftaran", true);
				ajax.setRequestHeader("Content-type", "application/json; charset=UTF-8");

				ajax.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						let data = JSON.parse(this.responseText);
						let html = "";
						for (let i = 0; i < data.length; i++) {
							html += "<p class='autocomplete' onclick='choose_pasien(this);' data-id='" + data[i]["id"] + "'>" + data[i]["nama"] + "</p>";
						}

						document.getElementById("pasien-list").innerHTML = html;
					}
				};

				ajax.send(JSON.stringify({
					"nama": nama.value
				}));
			}
		}

		function choose_pasien(self) {
			let pasien_id = self.getAttribute("data-id");

			document.getElementById("pasien_id").value = pasien_id;
			document.getElementById("pasien-list").innerHTML = "";
			document.getElementById("nama").value = self.innerHTML;
		}
	</script>
</body>

</html>