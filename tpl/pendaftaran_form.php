<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

?>

<div class="row">
	<div class="twelve columns">
		<div class="breadcrumb">
			<h2>Pendaftaran Entri</h2>
			<p><a href="/pendaftaran" class="button">Pendaftaran List</a></p>
		</div>
	</div>
</div>
<div class="box">
	<form method="post" action="/pendaftaran/save">
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
						<input type="text" name="id" id="id" value="<?= $inputs["id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
					</div>
					<div class="six columns">
						<label>Pasien ID</label>
						<input type="text" name="pasien_id" id="pasien_id" value="<?= $inputs["pasien_id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
					</div>
				</div>
			</div>
			<div class="six columns">
				<label>Nama</label>
				<input type="text" name="nama" id="nama" value="<?= $inputs["nama"] ?? "" ?>" onkeyup="autocomplete_pasien();" placeholder="Nama" class="u-full-width" autocomplete="off">
				<?php if (isset($errors["nama"])) : ?>
					<p style="color: red;"><?= $errors["nama"] ?></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<div id="pasien-list" class="autocomplete"></div>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label>Jenkel</label>
				<div style="display: flex; flex-direction: row; gap: 20px; justify-content: flex-start;">
					<div style="display: flex; flex-direction: row;">
						<div>Laki-Laki</div>
						<div><input type="radio" name="jenkel" id="jenkel_l" value="l" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "l") ? "checked" : "" ?> class="u-full-width"></div>
					</div>
					<div style="display: flex; flex-direction: row;">
						<div>Perempuan</div>
						<div><input type="radio" name="jenkel" id="jenkel_p" value="p" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "p") ? "checked" : "" ?> class="u-full-width"></div>
					</div>
				</div>
				<?php if (isset($errors["jenkel"])) : ?>
					<p style="color: red;"><?= $errors["jenkel"] ?></p>
				<?php endif; ?>
			</div>
			<div class="six columns">
				<label>Tanggal Lahir</label>
				<input type="date" name="lahir" id="lahir" value="<?= (isset($inputs["lahir"])) ? date("Y-m-d", strtotime($inputs["lahir"])) : date("Y-m-d") ?>" class="u-full-width">
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label>Alamat</label>
				<textarea name="alamat" id="alamat" class="u-full-width"><?= $inputs["alamat"] ?? "" ?></textarea>
				<?php if (isset($errors["alamat"])) : ?>
					<p style="color: red;"><?= $errors["alamat"] ?></p>
				<?php endif; ?>
			</div>
			<div class="six columns">
				<label>Keluhan</label>
				<textarea name="keluhan" id="keluhan" class="u-full-width"><?= $inputs["keluhan"] ?? "" ?></textarea>
				<?php if (isset($errors["keluhan"])) : ?>
					<p style="color: red;"><?= $errors["keluhan"] ?></p>
				<?php endif; ?>
			</div>
		</div>
		<input type="submit" class="button-primary">
	</form>
</div>

<script>
	function autocomplete_pasien() {
		let nama = document.getElementById("nama");
		if (nama.value.length >= 2) {
			document.getElementById("pasien-list").innerHTML = "Loading...";
			let ajax = new XMLHttpRequest();
			ajax.open("POST", "/autocomplete/pasien", true);
			ajax.setRequestHeader("Content-type", "application/json; charset=UTF-8");
			ajax.send(JSON.stringify({
				"nama": nama.value
			}));
			ajax.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					let data = JSON.parse(this.responseText);
					let html = "";
					for (let i = 0; i < data.length; i++) {
						html += `<p onclick="click_pasien(this);" 
										data-id="${data[i]["id"]}"
										data-nama="${data[i]["nama"]}"
										data-jenkel="${data[i]["jenkel"]}"
										data-lahir="${data[i]["lahir"]}"
										data-alamat="${data[i]["alamat"]}">
										<span class="color-1">${data[i]["id"]}</span>
										<span class="color-2">${data[i]["nama"]}</span>
										<span class="color-3">${data[i]["alamat"]}</span>
									</p>`;
					}
					document.getElementById("pasien-list").innerHTML = html;
				}
			};
		} else {
			document.getElementById("pasien-list").innerHTML = "";
		}
	}

	function click_pasien(self) {
		let pasien_id = self.getAttribute("data-id");
		let nama = self.getAttribute("data-nama");
		let jenkel = self.getAttribute("data-jenkel");
		let lahir = self.getAttribute("data-lahir");
		let alamat = self.getAttribute("data-alamat");
		document.getElementById("pasien_id").value = pasien_id;
		document.getElementById("nama").value = nama;
		if (jenkel == 'l') {
			document.getElementById("jenkel_l").checked = true;
			document.getElementById("jenkel_p").checked = false;
		} else {
			document.getElementById("jenkel_l").checked = false;
			document.getElementById("jenkel_p").checked = true;
		}
		document.getElementById("lahir").value = lahir;
		document.getElementById("alamat").value = alamat;
		document.getElementById("pasien-list").innerHTML = "";
	}
</script>